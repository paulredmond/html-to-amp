<?php

namespace Predmond\HtmlToAmp\Converter\Extensions;

use League\Event\EventInterface;
use League\Event\EmitterInterface as Emitter;
use Predmond\HtmlToAmp\ElementInterface;
use Predmond\HtmlToAmp\Converter\ConverterInterface;

class TwitterConverter implements ConverterInterface
{
    /**
     * @param EventInterface $event
     * @param ElementInterface $element
     * @param $tag
     */
    public function handleBlockquote(EventInterface $event, ElementInterface $element, $tag)
    {
        $classAttr = explode(' ', $element->getAttribute('class'));

        if (
            in_array('twitter-tweet', $classAttr)
            && false !== $twitterStatusId = $this->getStatusId($element)
        ) {
            $container = $element->createWritableElement('div', ['class' => 'amp-twitter-container']);
            $container->appendChild($this->createAmpTwitterTag($element, $twitterStatusId));
            $element->replaceWith($container);
            $event->stopPropagation();
        }
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            'blockquote' => 'handleBlockquote'
        ];
    }

    /**
     * Extract a Twitter Status ID from a blockquote
     * @param $element
     * @return mixed|bool|string
     */
    private function getStatusId(ElementInterface $element)
    {
        foreach ($element->getChildren() as $child) {
            if (preg_match('/status(?:es)?\/(\d+)/i', $child->getAttribute('href'), $match)) {
                return $match[1];
            }
        }

        return false;
    }

    /**
     * Create an amp-twitter tag
     *
     * @param ElementInterface $element
     * @param $twitterStatusId
     * @return ElementInterface
     */
    private function createAmpTwitterTag(ElementInterface $element, $twitterStatusId)
    {
        return $element->createWritableElement('amp-twitter', [
            'layout' => 'responsive',
            'data-tweetid' => $twitterStatusId,
            'width' => 390,
            'height' => 330,
            'data-cards' => 'hidden'
        ]);
    }
}
