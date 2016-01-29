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

    public function it_converts_an_image_to_amp_img()
    {
        // TODO: Fix this test
//        /** @var \DOMElement $node */
//        $node = (new \DOMDocument('1.0', 'utf-8'))
//            ->createElement('img');
//
//        $node->setAttribute('src', 'foo.jpg');
//
//        $this
//            ->convert(new Element($node))
//            ->shouldReturn('<amp-img src="foo.jpg"></amp-img>');
    }

    /** @test **/
    public function it_ignores_invalid_amp_attributes()
    {
        // TODO: Fix this test
//        /** @var \DOMElement $node */
//        $node = (new \DOMDocument('1.0', 'utf-8'))
//            ->createElement('img');
//
//        $node->setAttribute('src', 'foo.jpg');
//        $node->setAttribute('width', '300');
//        $node->setAttribute('height', '300');
//        $node->setAttribute('align', 'top');
//        $this
//            ->convert(new Element($node))
//            ->shouldReturn(
//                '<amp-img src="foo.jpg" width="300" height="300"></amp-img>'
//            );
    }

    /** @test **/
    public function it_has_supported_tags()
    {
        $this->getSupportedTags()->shouldReturn(['img']);
    }
}
