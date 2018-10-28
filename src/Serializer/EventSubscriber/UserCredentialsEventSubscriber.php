<?php

namespace DMT\WebservicesNl\Client\Serializer\EventSubscriber;

use DMT\WebservicesNl\Client\Request\LoginRequest;
use DMT\WebservicesNl\Client\Request\RequestInterface;
use DMT\WebservicesNl\Client\Serializer\AbstractSerializationVisitor;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;

class UserCredentialsEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;


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
                'method' => 'addCredentials',
                'format' => 'get',
            ],
        ];
    }

    /**
     * UserCredentialsEventSubscriber constructor.
     * @param string $username
     * @param string $password
     */
    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Add the user credentials to the request.
     *
     * @param ObjectEvent $event
     */
    public function addCredentials(ObjectEvent $event)
    {
        if ($event->getContext()->getDepth() === 0 && $event->getObject() instanceof RequestInterface) {
            /** @var AbstractSerializationVisitor $visitor */
            $visitor = $event->getContext()->getVisitor();

            if (!$event->getObject() instanceof LoginRequest) {
                $visitor->data = ['username' => $this->username, 'password' => $this->password] + $visitor->data;
            }
        }
    }
}
