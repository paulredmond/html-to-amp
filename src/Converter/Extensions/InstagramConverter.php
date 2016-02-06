<?php

namespace Predmond\HtmlToAmp\Converter\Extensions;

use League\Event\EventInterface;
use Predmond\HtmlToAmp\ElementInterface;
use Predmond\HtmlToAmp\Converter\ConverterInterface;

class InstagramConverter implements ConverterInterface
{
    public function hasInstagram(ElementInterface $element)
    {
        $classes = explode(' ', $element->getAttribute('class'));
        return in_array('instagram-media', $classes);
    }

    public function findTheCode($element) 
    {
        $href = $element->getAttribute('href');
        if ($href != '') {
            $parts = explode('/', $href);
            if (in_array('www.instagram.com', $parts)) {
                return $parts[count($parts)-2];
            }
        } 

        foreach($element->getChildren() as $child) {
            $shortcode = $this->findTheCode($child);
            if ($shortcode !== null) {
                return $shortcode;
            }
        }

        return null; 
                
    }

    public function getAmpInstagram(ElementInterface $element, $shortcode) {
        $attrs = [
            'layout' => "responsive",
            'width' => 600,
            'height' => 384,
            'data-shortcode' => $shortcode
        ];

        return $element->createWritableElement('amp-instagram', $attrs);
    }

    public function handleInstagram( EventInterface $event, ElementInterface $element) {

        if ($this->hasInstagram($element) == false) {
            return;
        }

        if ($element->hasChildren() == false) {
            return;
        }

        $event->stopPropagation();
        $shortcode = $this->findTheCode($element);
        $element->replaceWith($this->getAmpInstagram($element, $shortcode));
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return ['blockquote' => ['handleInstagram']];
    }
}
