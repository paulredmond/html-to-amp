<?php

namespace Predmond\HtmlToAmp\Converter;

use Predmond\HtmlToAmp\ElementInterface;

/**
 * A default converter that does nothing to the element
 *
 * Class NullConverter
 * @package Predmond\HtmlToAmp\Converter
 */
class NullConverter implements ConverterInterface
{

    /**
     * @param ElementInterface $element
     *
     * @return string
     */
    public function convert(ElementInterface $element)
    {
        return $element;
    }

    /**
     * @return string[]
     */
    public function getSupportedTags()
    {
        return [];
    }
}
