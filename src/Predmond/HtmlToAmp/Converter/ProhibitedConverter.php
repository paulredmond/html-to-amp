<?php

namespace Predmond\HtmlToAmp\Converter;

use Predmond\HtmlToAmp\ElementInterface;

/**
 * Removes Tags Prohibited in AMP HTML
 *
 * @see https://www.ampproject.org/docs/reference/spec.html
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
            && $element->getAttribute('http-equiv') === ''
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
