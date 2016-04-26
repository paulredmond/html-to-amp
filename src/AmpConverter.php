<?php

namespace Predmond\HtmlToAmp;

use DOMDocument;
use Predmond\HtmlToAmp\Converter\ConverterInterface;

class AmpConverter
{
    protected $environment;

    public function __construct(Environment $env = null)
    {
        $this->environment = $env;

        if ($this->environment === null) {
            $this->environment = Environment::createDefaultEnvironment();
        }
    }

    public function convert($html)
    {
        if (trim($html) === '') {
            return '';
        }

        $document = $this->createDocument($html);

        if (!($root = $document->getElementsByTagName('html')->item(0))) {
            throw new \InvalidArgumentException('Invalid HTML was provided');
        }

        $root = new Element($root);
        $this->convertChildren($root);

        $this->environment->getEventEmitter()->emit('after.convert', $document);

        $this->removeProhibited($document);
        $this->removeProhibitedAttributes($document);

        $this->environment->getEventEmitter()->emit('before.sanitize', $document);

        $ampHtml = $this->sanitize($document->saveHTML());

        return $ampHtml;
    }

    /**
     * @param string $html
     *
     * @return \DOMDocument
     */
    private function createDocument($html)
    {
        $document = new DOMDocument();

        libxml_use_internal_errors(true);
        $document->loadHTML('<?xml encoding="UTF-8">' . $html);
        $document->encoding = 'UTF-8';
        libxml_clear_errors();

        return $document;
    }

    private function convertChildren(ElementInterface $element)
    {
        if ($element->hasChildren()) {
            foreach ($element->getChildren() as $child) {
                $this->convertChildren($child);
            }
        }

        $this->convertToAmp($element);
    }

    private function convertToAmp(ElementInterface $element)
    {
        $tag = $element->getTagName();

        /** @var ConverterInterface $converter */
        $event = $this->environment->getEventEmitter()
            ->emit("convert.{$tag}", $element, $tag);
    }

    private function sanitize($html)
    {
        $html = preg_replace('/<!DOCTYPE [^>]+>/', '', $html);
        $unwanted = array('<html>', '</html>', '<body>', '</body>', '<head>', '</head>', '<?xml encoding="UTF-8">', '&#xD;');
        $html = str_replace($unwanted, '', $html);
        $html = trim($html, "\n\r\0\x0B");

        return $html;
    }

    private function removeProhibited(\DOMDocument $document)
    {
        // TODO: Config-based
        $xpath = '//' . implode('|//', [
            'base',
            'frame',
            'frameset',
            'object',
            'param',
            'applet',
            'embed',
            'form',
            'input',
            'textarea',
            'script',
            'select',
            'option',
            'meta',
            'map',
        ]);

        $elements = (new \DOMXPath($document))->query($xpath);

        /** @var \DOMElement $element */
        foreach ($elements as $element) {
            if ($element->nodeName === 'meta' && $element->getAttribute('http-equiv') === '') {
                continue;
            }

            if ($element->parentNode !== null) {
                $element->parentNode->removeChild($element);
            }
        }

        // Remove anchors with javascript in the href
        $anchors = (new \DOMXPath($document))
            ->query('//a[contains(@href, "javascript:")]');

        foreach ($anchors as $a) {
            if ($a->parentNode !== null) {
                $a->parentNode->removeChild($a);
            }
        }
    }

    /**
     * Removed prohibited attributes
     *
     * @param \DOMDocument $document
     */
    private function removeProhibitedAttributes(\DOMDocument $document)
    {
        // Globally invalid attributes
        // @todo more globally invalid attribute research. Does AMP have documentation for this?
        $invalidAttributes = [
            'align',
            'border',
            'contenteditable',
            'style',
        ];

        /**
         * Example xpath: "//*[@align]|//*[@style]"
         */
        $xpath = '//*[@' . implode(']|//*[@', $invalidAttributes) . ']';
        $elements = (new \DOMXPath($document))->query($xpath);

        /** @var \DOMElement $element */
        foreach ($elements as $element) {
            $this->removeElementAttributes($element, $invalidAttributes);
        }

        // Accepted attributes that are not valid on specific elements
        $xpathQueries = [
            '//table[@border]|//table[@cellpadding]|//table[@cellspacing]|//table[@width]' => ['border', 'cellpadding', 'cellspacing', 'width'],
            '//td[@width]|//td[@height]' => ['width', 'height'],
            '//ul|//ol' => ['compact', 'reversed', 'start', 'type'],
            '//blockquote[@cite]' => ['cite'],
        ];

        foreach ($xpathQueries as $query => $attributes) {
            $elements = (new \DOMXPath($document))->query($query);
            foreach ($elements as $element) {
                $this->removeElementAttributes($element, $attributes);
            }
        }
    }

    private function removeElementAttributes(
        \DOMElement $node,
        array $attributes = []
    ) {
        foreach ($attributes as $attribute) {
            $node->removeAttribute($attribute);
        }
    }
}
