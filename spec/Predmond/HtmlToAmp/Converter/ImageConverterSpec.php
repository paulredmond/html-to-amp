<?php

namespace spec\Predmond\HtmlToAmp\Converter;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Predmond\HtmlToAmp\Element;

class ImageConverterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Predmond\HtmlToAmp\Converter\ImageConverter');
    }

    /** @test **/
    public function it_converts_an_image_to_amp_img()
    {
        /** @var \DOMElement $node */
        $node = (new \DOMDocument('1.0', 'utf-8'))
            ->createElement('img');

        $node->setAttribute('src', 'foo.jpg');

        $this
            ->convert(new Element($node))
            ->shouldReturn('<amp-img src="foo.jpg"></amp-img>');
    }
}
