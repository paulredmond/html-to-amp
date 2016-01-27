<?php

namespace spec\Predmond\HtmlToAmp;

use DOMNode;
use DOMDocument;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ElementSpec extends ObjectBehavior
{
    function let()
    {
        $node = (new DOMDocument('1.0', 'utf-8'))
            ->createElement('div', 'Hello world!');

        $node->setAttribute('id', 'welcome');
        $node->setAttribute('class', 'welcome big');

        $this->beConstructedWith($node);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Predmond\HtmlToAmp\Element');
        $this->shouldHaveType('Predmond\HtmlToAmp\ElementInterface');
    }

    function it_can_get_the_tag_name()
    {
        $this->getTagName()->shouldReturn("div");
    }

    public function it_can_get_the_element_value()
    {
        $this->getValue()->shouldReturn('Hello world!');
    }

    /** @test **/
    public function it_can_get_an_attribute()
    {
        $this->getAttribute('id')->shouldReturn('welcome');
    }

    /** @test **/
    public function it_gets_an_attribute_of_a_non_DOMElement()
    {
        $node = (new DOMDocument('1.0', 'utf-8'))
            ->createTextNode('Hello World');

        $this->beConstructedWith($node);

        $this->getAttribute('foo')->shouldReturn('');
    }

    /** @test **/
    public function it_can_get_all_attributes()
    {
        $this->getAttributes()->shouldReturn([
            'id' => 'welcome',
            'class' => 'welcome big'
        ]);
    }

    /** @test **/
    public function it_can_get_attributes_for_a_non_DOMElement()
    {
        $node = (new DOMDocument('1.0', 'utf-8'))
            ->createTextNode('Hello World');

        $this->beConstructedWith($node);

        $this->getAttributes()->shouldReturn([]);
    }

    /** @test **/
    public function it_confirms_child_nodes()
    {
        $node = (new DOMDocument('1.0', 'utf-8'))
            ->createElement('div', '<p>Hello World</p>');

        $this->beConstructedWith($node);

        $this->hasChildren()->shouldReturn(true);
    }
    
    /** @test **/
    public function it_confirms_absence_of_child_nodes()
    {
        $node = (new DOMDocument('1.0', 'utf-8'))
            ->createElement('div');

        $this->beConstructedWith($node);

        $this->hasChildren()->shouldReturn(false);
    }

    /** @test **/
    public function it_can_remove_itself_from_the_document()
    {
        $document   = new DOMDocument('1.0', 'utf-8');
        $parentNode = $document->createElement('div');
        $node       = $document->createElement('p', 'Hello World');

        $parentNode->appendChild($node);
        $document->appendChild($parentNode);

        $this->beConstructedWith($node);
        $this->remove()->shouldReturn($node);
    }
}
