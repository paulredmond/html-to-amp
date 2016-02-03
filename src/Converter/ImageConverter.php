<?php

namespace Predmond\HtmlToAmp\Converter;

use League\Event\EventInterface;
use Predmond\HtmlToAmp\ElementInterface;

class ImageConverter implements ConverterInterface
{
    private $validAttributes = [
        'src', 'width', 'height', 'srcset', 'alt', 'attribution'
    ];

    public function handleTagImg(EventInterface $event, ElementInterface $element)
    {
        /** @var ElementInterface $ampImg */
        $ampImg = $element->createWritableElement('amp-img');

        foreach ($this->validAttributes as $attribute) {
            if ($element->getAttribute($attribute)) {
                $ampImg->setAttribute(
                    $attribute,
                    $element->getAttribute($attribute)
                );
            }
        }

        return $element->replaceWith($ampImg);
    }

    public function getSubscribedEvents()
    {
        return [
            'img' => ['handleTagImg']
        ];
    }
}
