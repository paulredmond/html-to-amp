<?php

namespace spec\Predmond\HtmlToAmp\Converter;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Predmond\HtmlToAmp\Element;
use League\Event\EventInterface;
use Predmond\HtmlToAmp\ElementInterface;

class ImageConverterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Predmond\HtmlToAmp\Converter\ImageConverter');
        $this->shouldHaveType('Predmond\HtmlToAmp\Converter\ConverterInterface');
    }

    public function it_converts_an_image_to_amp_img(
        ElementInterface $ampImg,
        EventInterface $event,
        ElementInterface $element
    ) {
        $ampImg->setAttribute('src', 'foo.jpg')->shouldBeCalled();
        $ampImg->setAttribute('width', 300)->shouldBeCalled();
        $ampImg->setAttribute('height', 250)->shouldBeCalled();
        $ampImg->setAttribute('class', 'amp-img')->shouldBeCalled();
        $ampImg->setAttribute('srcset', '')->shouldNotBeCalled();
        $ampImg->setAttribute('alt', '')->shouldNotBeCalled();
        $ampImg->setAttribute('attribution', '')->shouldNotBeCalled();

        $element->getAttribute('src')->shouldBeCalled()->willReturn('foo.jpg');
        $element->getAttribute('width')->shouldBeCalled()->willReturn(300);
        $element->getAttribute('height')->shouldBeCalled()->willReturn(250);
        $element->getAttribute('class')->shouldBeCalled()->willReturn('amp-img');
        $element->getAttribute('srcset')->shouldBeCalled()->willReturn('');
        $element->getAttribute('alt')->shouldBeCalled()->willReturn('');
        $element->getAttribute('attribution')->shouldBeCalled()->willReturn('');

        $element
            ->createWritableElement('amp-img')
            ->willReturn($ampImg);

        $element->replaceWith($ampImg)->shouldBeCalled();

        $this->handleTagImg($event, $element);
    }

    /** @test **/
    public function it_has_subscribed_events()
    {
        $this->getSubscribedEvents()->shouldReturn([
            'img' => ['handleTagImg']
        ]);
    }
}
