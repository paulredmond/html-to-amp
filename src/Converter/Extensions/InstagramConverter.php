<?php

namespace Predmond\HtmlToAmp\Converter\Extensions;

use League\Event\EventInterface;
use Predmond\HtmlToAmp\ElementInterface;
use Predmond\HtmlToAmp\Converter\ConverterInterface;

class InstagramConverter implements ConverterInterface
{
    public function convertToAmp(ElementInterface $element)
    {
        if ($element->hasChildren() == false) {
            return false;
        }

        $hasClass = in_array('instagram-media', explode(' ', $element->getAttribute('class')));
        $hasAttr = array_key_exists('data-instgrm-version', $element->getAttributes());

        return $hasClass || $hasAttr;
    }

    public function getEmbedShortcode($element) 
    {
        $href = $element->getAttribute('href');
        if ($href != '') {
            if (1 === preg_match('/(?:instagr\.am|instagram\.com)\/p\/([^\/]+)\/?$/i', $href, $matches)) {
                    return $matches[1];
            }
        } 

        foreach($element->getChildren() as $child) {
            $shortcode = $this->getEmbedShortcode($child);
            if ($shortcode !== null) {
                return $shortcode;
            }
        }

        return null; 
                
    }

    public function handleInstagram( EventInterface $event, ElementInterface $element) {

        if ($this->convertToAmp($element) == false) {
            return;
        }

        $shortcode = $this->getEmbedShortcode($element);

        if ($shortcode == null) {
            return;
        }

        $attrs = [
            'layout' => "responsive",
            'width' => 600,
            'height' => 384,
            'data-shortcode' => $shortcode
        ];

        $element->replaceWith($element->createWritableElement('amp-instagram', $attrs));
        $event->stopPropagation();
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return ['blockquote' => ['handleInstagram']];
    }
}
