<?php

namespace spec\Predmond\HtmlToAmp;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AmpConverterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Predmond\HtmlToAmp\AmpConverter');
    }

    public function it_converts_spaces_to_an_empty_string()
    {
        $this->convert('   ')->shouldReturn('');
    }

    function it_converts_html_to_amp()
    {
        $this->convert(implode('', [
            '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>',
            '<img src="foo.jpg">',
            '<p>Aut blanditiis exercitationem in, incidunt odit optio.</p>'
        ]))->shouldReturn(implode('', [
            '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>',
            '<amp-img src="foo.jpg"></amp-img>',
            '<p>Aut blanditiis exercitationem in, incidunt odit optio.</p>'
        ]));
    }
}
