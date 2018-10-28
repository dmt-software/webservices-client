<?php

namespace DMT\WebservicesNl\Client\Serializer\Handler;

use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\DateHandler;

class GenericDateHandler extends DateHandler
{
    /**
     * @static array
     */
    const DATE_TYPES = [
        'DateTime',
        'DateTimeImmutable',
        'DateInterval'
    ];

    /**
     * @static array
     */
    const FORMATS = [
        'xml' => [
            'soap',
            'xml',
        ],
        'rpc' => [
            'get'
        ]
    ];

    /**
     * {@inheritdoc}
     */
    public static function getSubscribingMethods(): array
    {
        $mapping = function ($type) use (&$format, &$methods, &$globalType) {
            $methods[] = [
                'type' => $type,
                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                'format' => $format,
                'method' => 'deserialize' . $type . 'From' . ucfirst($globalType)
            ];
            $methods[] = [
                'type' => $type,
                'format' => $format,
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'method' => 'serialize' . $type,
            ];
        };

        $methods = [];
        foreach (static::FORMATS as $globalType => $formats) {
            foreach ($formats as $format) {
                array_map($mapping, static::DATE_TYPES);
            }
        }

        return $methods;
    }
}
