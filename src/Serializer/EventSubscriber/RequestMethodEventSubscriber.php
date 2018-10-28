<?php

namespace DMT\WebservicesNl\Client\Serializer\EventSubscriber;

use DMT\WebservicesNl\Client\Request\RequestInterface;
use DMT\WebservicesNl\Client\Serializer\AbstractSerializationVisitor;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;

class RequestMethodEventSubscriber implements EventSubscriberInterface
{
    const REQUEST_REGEX = '~^(DMT\\\WebservicesNl\\\)([^\\\]+)(\\\.*)?\\\(.*)Request$~';

    /**
     * Returns the events to which this class has subscribed.
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            [
                'event' => 'serializer.post_serialize',
                'method' => 'addRequestMethod',
                'format' => 'get',
            ],
        ];
    }

    /**
     * Add the method name of the request.
     *
     * @param ObjectEvent $event
     */
    public function addRequestMethod(ObjectEvent $event)
    {
        if ($event->getContext()->getDepth() === 0 && $event->getObject() instanceof RequestInterface) {
            /** @var AbstractSerializationVisitor $visitor */
            $visitor = $event->getContext()->getVisitor();

            $methodName = null;
            if (preg_match(static::REQUEST_REGEX, get_class($event->getObject()), $match)) {
                $methodName = lcfirst(($match[2] === 'Client' ? '' : $match[2]) . $match[4]);
            }

            $visitor->data = compact('methodName') + $visitor->data;
        }
    }
}
