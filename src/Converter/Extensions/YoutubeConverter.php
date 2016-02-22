<?php

namespace Predmond\HtmlToAmp\Converter\Extensions;

use League\Event\EventInterface;
use League\Event\EmitterInterface as Emitter;
use Predmond\HtmlToAmp\ElementInterface;
use Predmond\HtmlToAmp\Converter\ConverterInterface;

class YoutubeConverter implements ConverterInterface
{

    public function handleIframe(EventInterface $event, ElementInterface $element)
    {
        $src = $element->getAttribute('src');
        if (1 === preg_match('/youtube\.com\/(?:v|embed)\/([a-zA-z0-9_-]+)/i', $src, $match)) {
            $element->replaceWith($this->createAmpTag($element, $match[1]));
            $event->stopPropagation();
        }
    }

    public function handleObject(EventInterface $event, ElementInterface $element)
    {
        $embedCode = false;

        /** @var ElementInterface $child */
        foreach ($element->getChildren() as $child) {
            if (1 === preg_match('/youtube\.com\/(?:v|embed)\/([a-zA-z0-9_-]+)/i', $child->getAttribute('value'), $match)) {
                $embedCode = $match[1];
            }
        }

        if ($embedCode !== false) {
            $element->replaceWith($this->createAmpTag($element, $embedCode));
            $event->stopPropagation();
        }
    }

    /**
     * @param ElementInterface $element
     * @param $embedCode
     * @return ElementInterface
     */
    private function createAmpTag(ElementInterface $element, $embedCode)
    {
        return $element->createWritableElement('amp-youtube', [
            'data-videoid' => $embedCode,
            'layout' => 'responsive',
            'width' => '560',
            'height' => '315'
        ]);
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            'iframe' => ['handleIframe', Emitter::P_HIGH],
            'object' => ['handleObject', Emitter::P_HIGH]
        ];
    }
}
