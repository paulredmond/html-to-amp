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

    function it_should_remove_prohibited_tags()
    {
        $convert = [
            '<base href="http://example.com/dir/" />',
            '<meta http-equiv="Content-Language" content="en">',
            '<meta name="foo" content="bar" />',
            '<form>',
            '<select name="option"><option value="1">Option 1</option></select>',
            '<textarea name="description">Foo</textarea>',
            '<input type="submit" value="Push Me">',
            '</form>',
            '<p>Hello World <a href="http://example.com">Example</a></p>',
            '<a href="javascript:alert(\'foo\')">Alert Foo</a>',
            '<object width="400" height="400" data="foo.swf">',
            '<param name="foo" value="bar"></object>',
            '</object>',
            '<script src="itsatrap.js"></script>',
            '<embed src="foo.swf" />',
        ];

        $this
            ->convert(implode("\n", $convert))
            ->shouldReturn(implode("\n", [
                '<meta name="foo" content="bar">',
                '<p>Hello World <a href="http://example.com">Example</a></p>'
            ]));
    }
}
