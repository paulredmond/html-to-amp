<?php

namespace Predmond\HtmlToAmp;

use Predmond\HtmlToAmp\Converter\ConverterInterface;
use Predmond\HtmlToAmp\Converter\ImageConverter;
use Predmond\HtmlToAmp\Converter\NullConverter;
use Predmond\HtmlToAmp\Converter\ProhibitedConverter;

class Environment
{
    const DEFAULT_CONVERTER = 'default';

    protected $converters = [];

    public static function createDefaultEnvironment()
    {
        $env = new static();

        $env
            ->addConverter(new NullConverter())
            ->addConverter(new ImageConverter())
            ->addConverter(new ProhibitedConverter());

        return $env;
    }

    public function addConverter(ConverterInterface $converter)
    {
        foreach ($converter->getSupportedTags() as $tag) {
            $this->converters[$tag] = $converter;
        }

        if ($converter instanceof NullConverter) {
            $this->converters['default'] = $converter;
        }

        return $this;
    }

    public function getConverterByTag($tag)
    {
        if (isset($this->converters[$tag])) {
            return $this->converters[$tag];
        }

        return $this->converters[self::DEFAULT_CONVERTER];
    }
}
