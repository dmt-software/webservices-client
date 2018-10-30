<?php

namespace DMT\WebservicesNl\Client\Serializer\EventSubscriber;

use DMT\WebservicesNl\Client\Model\PagedResult;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\PreDeserializeEvent;

/**
 * Class PagedResultHandler
 *
 * @package DMT\WebservicesNl\Client
 */
class PagedResultEventSubscriber implements EventSubscriberInterface
{

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            [
                'event' => 'serializer.pre_deserialize',
                'format' => 'xml',
                'method' => 'deserializePagedResult',
            ]
        ];
    }

    /**
     * @param PreDeserializeEvent $event
     */
    public function deserializePagedResult(PreDeserializeEvent $event)
    {
        if (is_a($event->getType()['name'], PagedResult::class, true)) {
            $metadata = $event->getContext()->getMetadataFactory()->getMetadataForClass($event->getType()['name']);
            $metadata->propertyMetadata['results']->xmlEntryName = 'entry';
        }
    }
}
