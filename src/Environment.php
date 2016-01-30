<?php

namespace Predmond\HtmlToAmp;

use League\Event\Emitter;
use League\Event\EmitterInterface;
use Predmond\HtmlToAmp\Converter\ConverterInterface;
use Predmond\HtmlToAmp\Converter\ImageConverter;
use Predmond\HtmlToAmp\Converter\NullConverter;
use Predmond\HtmlToAmp\Converter\ProhibitedConverter;

class Environment
{
    /**
     * @var EmitterInterface
     */
    private $eventEmitter;

    public function __construct(EmitterInterface $eventEmitter = null)
    {
        $this->eventEmitter = $eventEmitter;

        if ($this->eventEmitter === null) {
            $this->eventEmitter = new Emitter();
        }
    }

    public static function createDefaultEnvironment()
    {
        $env = new static();
        $env->addConverter(new ImageConverter());

        return $env;
    }

    public function addConverter(ConverterInterface $converter)
    {
        foreach ($converter->getSubscribedEvents() as $tag => $event) {
            $eventName = stripos($tag, 'convert.') === 0 ?
                $tag : "convert.{$tag}";

            if (is_string($event)) {
                $event = [$event];
            }

            $event = array_values($event);
            list($callbackName, $priority) = count($event) > 1 ?
                [$event[0], $event[1]] : [$event[0], EmitterInterface::P_NORMAL];

            $this->eventEmitter->addListener(
                $eventName,
                [$converter, $callbackName],
                $priority
            );
        }

        return $this;
    }

    /**
     * @return Emitter|EmitterInterface
     */
    public function getEventEmitter()
    {
        return $this->eventEmitter;
    }
}
