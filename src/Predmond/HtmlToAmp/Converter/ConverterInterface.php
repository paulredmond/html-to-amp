<?php

namespace Predmond\HtmlToAmp\Converter;

use Predmond\HtmlToAmp\ElementInterface;

interface ConverterInterface
{
    /**
     * @param ElementInterface $element
     *
     * @return string
     */
    public function convert(ElementInterface $element);

    /**
     * @return string[]
     */
    public function getSupportedTags();
}
