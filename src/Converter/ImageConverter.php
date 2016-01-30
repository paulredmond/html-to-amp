<?php

namespace Predmond\HtmlToAmp\Converter;

use League\Event\EventInterface;
use Predmond\HtmlToAmp\ElementInterface;

class ImageConverter implements ConverterInterface
{
    private $validAttributes = [
        'src', 'width', 'height', 'srcset', 'alt', 'attribution'
    ];

    /**
     * @param ElementInterface $element
     *
     * @return string
     */
    public function convert(ElementInterface $element)
    {
        $ampImg = $element->createWritableElement('amp-img');
        $ampImg->setAttribute('src', $element->getAttribute('src'));
        $ampImg->setAttribute('width', $element->getAttribute('width'));
        $ampImg->setAttribute('height', $element->getAttribute('height'));

        return $element->replaceWith($ampImg);
    }

    public function handleTagImg(EventInterface $event, ElementInterface $element)
    {
        $ampImg = $element->createWritableElement('amp-img');
        $ampImg->setAttribute('src', $element->getAttribute('src'));
        $ampImg->setAttribute('width', $element->getAttribute('width'));
        $ampImg->setAttribute('height', $element->getAttribute('height'));

        return $element->replaceWith($ampImg);
    }

    public function getSubscribedEvents()
    {
        return [
            'img' => ['handleTagImg']
        ];
    }

//    private function attributesToString(ElementInterface $element)
//    {
//        $nodeAttributes = $element->getAttributes();
//        $validAttributes = array_intersect_key(
//            array_flip($this->validAttributes),
//            $element->getAttributes()
//        );
//
//        $validAttributes = array_keys($validAttributes);
//
//        if (empty($validAttributes)) {
//            return '';
//        }
//
//        $out = array_map(function ($value, $property) use ($validAttributes) {
//            if (in_array($property, $validAttributes)) {
//                return sprintf('%s="%s"', $property, $value);
//            }
//        }, array_values($nodeAttributes), array_keys($nodeAttributes));
//
//        return trim(implode(' ', $out));
//    }
}
