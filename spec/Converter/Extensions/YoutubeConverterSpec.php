<?php

namespace spec\Predmond\HtmlToAmp\Converter\Extensions;

use Prophecy\Argument;
use PhpSpec\ObjectBehavior;
use Predmond\HtmlToAmp\Element;
use League\Event\EventInterface;
use Predmond\HtmlToAmp\ElementInterface;
use Predmond\HtmlToAmp\Converter\Extensions\YoutubeConverter;

class YoutubeConverterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(YoutubeConverter::class);
    }

    function it_can_convert_a_youtube_iframe_tag(
        EventInterface $event,
        ElementInterface $element,
        Element $writeableElement,
        Element $ampYoutubeElement
    ) {
        $event
            ->stopPropagation()
            ->shouldBeCalled()
        ;
        
        $writeableElement
            ->appendChild($ampYoutubeElement)
            ->shouldBeCalled()
        ;
        
        $element
            ->getAttribute('src')
            ->shouldBeCalled()
            ->willReturn('http://www.youtube.com/embed/XGSy3_Czz8k?autoplay=1')
        ;

        $element
            ->createWritableElement('div', [
                'class' => 'youtube-container'
            ])
            ->shouldBeCalled()
            ->willReturn($writeableElement)
        ;

        $element->replaceWith($writeableElement)->shouldBeCalled();

        $element
            ->createWritableElement(
                'amp-youtube',
                [
                    'data-videoid' => 'XGSy3_Czz8k',
                    "layout" => "responsive",
                    "width" => "560",
                    "height" => "315"
                ]
            )
            ->shouldBeCalled()
            ->willReturn($ampYoutubeElement)
        ;

        $this->handleIframe($event, $element);
    }

    function it_skips_converting_non_youtube_iframes(
        EventInterface $event,
        ElementInterface $element
    ) {
        $element
            ->getAttribute('src')
            ->shouldBeCalled()
            ->willReturn('http://metube.com/embed/XGSy3_Czz8k')
        ;

        $event->stopPropagation()->shouldNotBeCalled();
        $element->createWritableElement('div', [
            'class' => 'youtube-container'
        ])->shouldNotBeCalled();

        $this->handleIframe($event, $element);
    }

    function it_converts_a_youtube_object_tag(
        EventInterface $event,
        ElementInterface $element,
        Element $child,
        Element $containerElement,
        Element $ampYoutubeElement
    ) {
        $event
            ->stopPropagation()
            ->shouldBeCalled()
        ;
        
        $element
            ->getChildren()
            ->shouldBeCalled()
            ->willReturn([$child])
        ;

        $child
            ->getAttribute('value')
            ->shouldBeCalled()
            ->willReturn('http://www.youtube.com/embed/XGSy3_Czz8k?autoplay=1')
        ;
        
        $element
            ->createWritableElement('div', [
                'class' => 'youtube-container'
            ])
            ->shouldBeCalled()
            ->willReturn($containerElement)
        ;

        $containerElement
            ->appendChild($ampYoutubeElement)
            ->shouldBeCalled()
        ;
        
        $element
            ->replaceWith($containerElement)
            ->shouldBeCalled()
        ;

        $element
            ->createWritableElement(
                'amp-youtube',
                [
                    'data-videoid' => 'XGSy3_Czz8k',
                    'layout' => 'responsive',
                    'width' => '560',
                    'height' => '315'
                ]
            )
            ->shouldBeCalled()
            ->willReturn($ampYoutubeElement)
        ;

        $this->handleObject($event, $element);
    }

    function it_skips_converting_non_youtube_object_tags(
        EventInterface $event,
        ElementInterface $element,
        ElementInterface $childElement
    ) {
        $element
            ->getChildren()
            ->shouldBeCalled()
            ->willReturn([$childElement])
        ;

        $childElement
            ->getAttribute('value')
            ->shouldBeCalled()
            ->willReturn('http://metube.com/embed/XGSy3_Czz8k')
        ;

        $event->stopPropagation()->shouldNotBeCalled();
        $element->createWritableElement('div', [
            'class' => 'youtube-container'
        ])->shouldNotBeCalled();

        $this->handleObject($event, $element);
    }
}
