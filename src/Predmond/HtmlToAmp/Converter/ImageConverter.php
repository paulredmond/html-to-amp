<?php

namespace Predmond\HtmlToAmp\Converter;

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

        return $element->replaceWith($ampImg);
    }

    /**
     * @return array
     */
    public function getSupportedTags()
    {
        return ['img'];
    }

    private function attributesToString(ElementInterface $element)
    {
        $nodeAttributes = $element->getAttributes();
        $validAttributes = array_intersect_key(
            array_flip($this->validAttributes),
            $element->getAttributes()
        );

        $validAttributes = array_keys($validAttributes);

        if (empty($validAttributes)) {
            return '';
        }

        $out = array_map(function ($value, $property) use ($validAttributes) {
            if (in_array($property, $validAttributes)) {
                return sprintf('%s="%s"', $property, $value);
            }
        }, array_values($nodeAttributes), array_keys($nodeAttributes));

        return trim(implode(' ', $out));
    }
}
