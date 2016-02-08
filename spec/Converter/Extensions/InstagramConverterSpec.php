<?php

namespace spec\Predmond\HtmlToAmp\Converter\Extensions;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use \Prophecy\Prophet;
use Predmond\HtmlToAmp\Element;
use League\Event\EventInterface;
use Predmond\HtmlToAmp\ElementInterface;
use DOMElement;

class InstagramConverterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Predmond\HtmlToAmp\Converter\Extensions\InstagramConverter');
        $this->shouldHaveType('Predmond\HtmlToAmp\Converter\ConverterInterface');
    }

    public function it_has_subscribed_events()
    {
        $this->getSubscribedEvents()->shouldReturn([
            'blockquote' => ['handleInstagram']
        ]);
    }

    public function it_has_instagram(ElementInterface $element)
    {
        $element->getAttribute('class')->willReturn('instagram-media or more');
        $this->hasInstagram($element)->shouldReturn(true);
    }

    public function it_finds_the_instagram_code(ElementInterface $element)
    {
        $prophet = new Prophet();

        $anchor = $prophet->prophesize('Predmond\HtmlToAmp\Element');
        $anchor->getAttribute('href')->willReturn('https://www.instagram.com/p/AAA-aaa/');

        $sibling = $prophet->prophesize('Predmond\HtmlToAmp\Element');
        $sibling->getAttribute('href')->willReturn('');

        $parent = $prophet->prophesize('Predmond\HtmlToAmp\Element');
        $parent->getAttribute('href')->willReturn('');
        $parent->getChildren()->willReturn([ $anchor, $sibling ]);

        $element->getAttribute('href')->willReturn('');
        $element->getChildren()->willReturn([ $parent]);

        $this->findTheCode($element)->shouldBe('AAA-aaa');
    }

    public function it_returns_amp_instagram(ElementInterface $element) 
    {
        $prophet = new Prophet();
        $newElement = $prophet->prophesize('Predmond\HtmlToAmp\Element');

        $attrs = [
            'layout' => "responsive",
            'width' => 600,
            'height' => 384,
            'data-shortcode' => 'BBB-bbb'
        ];

        $code = 'BBB-bbb';

        $element->createWritableElement('amp-instagram', $attrs)->willReturn($newElement);
        $this->getAmpInstagram($element, $code)->shouldReturnAnInstanceOf(\Predmond\HtmlToAmp\Element::class);
    }

    public function it_replaces_element_class_instagram_with_amp(
        EventInterface $event,
        ElementInterface $element
    ) {
        $this->handleInstagram($event, $element);
    }

}
