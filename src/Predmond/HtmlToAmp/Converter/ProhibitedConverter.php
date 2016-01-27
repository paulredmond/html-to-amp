<?php

namespace Predmond\HtmlToAmp\Converter;

use Predmond\HtmlToAmp\ElementInterface;

/**
 * Converts prohibited HTML tags to null
 *
 * @package Predmond\HtmlToAmp\Converter
 */
class ProhibitedConverter implements ConverterInterface
{

    /**
     * @param ElementInterface $element
     *
     * @return string
     */
    public function convert(ElementInterface $element)
    {
        if (
            $element->getTagName() === 'meta'
            && empty($element->getAttribute('http-equiv'))
        ) {
            return $element;
        }

        return $element->remove();
    }

    /**
     * @return string[]
     */
    public function getSupportedTags()
    {
        return [
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
            'select',
            'option',
            'meta'
        ];
    }
}
