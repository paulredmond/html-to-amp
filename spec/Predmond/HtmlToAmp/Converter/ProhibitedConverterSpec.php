<?php

namespace spec\Predmond\HtmlToAmp\Converter;

use Prophecy\Argument;
use PhpSpec\ObjectBehavior;
use Predmond\HtmlToAmp\Element;
use Predmond\HtmlToAmp\ElementInterface;

class ProhibitedConverterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Predmond\HtmlToAmp\Converter\ProhibitedConverter');
    }

    public function it_can_convert_prohibited_tags()
    {
        /** @var \DOMElement $node */
        $node = (new \DOMDocument('1.0', 'utf-8'))
            ->createElement('frame');

        $this->convert(new Element($node))->shouldReturn(false);
    }

    public function it_prohibits_meta_tags_with_http_equiv(ElementInterface $element)
    {
        $element->getTagName()->willReturn('meta');
        $element->getAttribute('http-equiv')->willReturn('refresh');
        $element->remove()->shouldBeCalled();

        $this->convert($element);
    }

    public function it_allows_meta_tags_without_http_equiv(ElementInterface $element)
    {
        $element->getTagName()->willReturn('meta');
        $element->getAttribute('http-equiv')->willReturn('');
        $element->remove()->shouldNotBeCalled();

        $this->convert($element);
    }

    public function it_has_supported_tags()
    {
        $tags = [
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

        foreach ($tags as $tag) {
            $this->getSupportedTags()->shouldContain($tag);
        }
    }
}
