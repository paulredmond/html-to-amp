<?php

namespace Predmond\HtmlToAmp;

use Predmond\HtmlToAmp\Converter\ConverterInterface;

class AmpConverter
{
    protected $environment;

    public function __construct()
    {
        $this->environment = Environment::createDefaultEnvironment();
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
        $document = new \DOMDocument();

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
        $converter = $this->environment->getConverterByTag($tag);

        return $converter->convert($element);
    }

    private function sanitize($html)
    {
        $html = preg_replace('/<!DOCTYPE [^>]+>/', '', $html);
        $unwanted = array('<html>', '</html>', '<body>', '</body>', '<head>', '</head>', '<?xml encoding="UTF-8">', '&#xD;');
        $html = str_replace($unwanted, '', $html);
        $html = trim($html, "\n\r\0\x0B");

        return $html;
    }
}
