<?php

namespace spec\Predmond\HtmlToAmp;

use DOMNode;
use DOMDocument;
use Prophecy\Argument;
use PhpSpec\ObjectBehavior;
use Predmond\HtmlToAmp\Element;

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

    function it_responds_to_getNode()
    {
        $this->getNode()->shouldReturnAnInstanceOf('DOMElement');
    }

    function it_can_get_the_tag_name()
    {
        $this->getTagName()->shouldReturn("div");
    }

    public function it_responds_to_getValue()
    {
        $this->getValue()->shouldReturn('Hello world!');
    }

    /** @test **/
    public function it_can_get_an_attribute()
    {
        $this->getAttribute('id')->shouldReturn('welcome');
        $this->getAttribute('foo')->shouldReturn('');
    }

    public function it_can_set_an_attribute()
    {
        $this->setAttribute('foo', 'bar')->shouldReturn(null);
        $this->getAttribute('foo')->shouldReturn('bar');
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

    public function it_can_get_the_parent()
    {
        $doc = new DOMDocument('1.0', 'utf-8');
        $div = $doc->createElement('div');
        $p = $doc->createElement('p', 'Hello World');
        $div->appendChild($p);

        $this->beConstructedWith($p);
        $this->getParent()->shouldReturnAnInstanceOf('Predmond\HtmlToAmp\Element');
    }

    public function it_can_append_an_element(Element $element)
    {

        $doc = new DOMDocument('1.0', 'utf-8');
        $div = $doc->createElement('div');
        $p = $doc->createElement('p', 'Hello World');

        $element->getNode()->willReturn($p);

        $this->beConstructedWith($div);

        $this->appendChild($element)->shouldReturn(null);
    }

    public function it_can_create_a_new_element_from_the_owner_document()
    {
        $doc = new DOMDocument('1.0', 'utf-8');
        $node = $doc->createElement('div', 'Hello world!');
        $this->beConstructedWith($node);

        $p = $this
            ->createWritableElement('p', ['class' => 'foo'])
            ->shouldReturnAnInstanceOf(Element::class);

        $this->appendChild($p)->shouldReturn(null);
    }

    public function it_can_replace_itself_in_the_document_with_another_element()
    {
        $doc = new DOMDocument('1.0', 'utf-8');
        $parent = $doc->createElement('div');
        $container = $doc->createElement('div', 'hello world');
        $parent->appendChild($container);

        $this->beConstructedWith($container);

        $p = $this
            ->createWritableElement('p', ['class' => 'foo'])
            ->shouldReturnAnInstanceOf(Element::class);

        $this->replaceWith($p)->shouldReturnAnInstanceOf(\DOMElement::class);
    }

    public function it_should_not_replace_itself_if_a_parent_node_is_not_found()
    {
        $p = $this->createWritableElement('p');

        $this->replaceWith($p)->shouldBe(false);
    }

    public function it_removes_itself_from_the_document()
    {
        $doc = new DOMDocument('1.0', 'utf-8');
        $parent = $doc->createElement('div');
        $container = $doc->createElement('div', 'hello world');
        $parent->appendChild($container);

        $this->beConstructedWith($container);

        $this->remove()->shouldReturn($this->getNode());
    }

    public function it_should_not_remove_itself_without_a_parent_node()
    {
        $this->remove()->shouldBe(false);
    }

    public function it_can_get_child_elements()
    {
        $doc = new DOMDocument('1.0', 'utf-8');
        $parent = $doc->createElement('div');
        $container = $doc->createElement('div', 'hello world');
        $parent->appendChild($container);

        $this->beConstructedWith($parent);

        $this->getChildren()->shouldContainElements();
    }

    public function it_should_not_return_child_elements_when_none_exist()
    {
        $node = (new DOMDocument('1.0', 'utf-8'))
            ->createElement('div');

        $this->beConstructedWith($node);
        
        $this->getChildren()->shouldNotContainElements();
    }

    public function getMatchers()
    {
        return [
            'containElements' => function (array $subject) {
                $count = 0;
                $total = count($subject);

                foreach ($subject as $node) {
                    if ($node instanceof Element) {
                        $count++;
                    }
                }

                return ($count === $total && $count > 0);
            }
        ];
    }
}
