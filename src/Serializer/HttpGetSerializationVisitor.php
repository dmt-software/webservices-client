<?php

namespace DMT\WebservicesNl\Client\Serializer;

/**
 * Class HttpGetSerializationVisitor
 *
 * @package DMT\WebservicesNl\Client
 */
class HttpGetSerializationVisitor extends AbstractSerializationVisitor
{
    /**
     * @return mixed
     */
    public function getResult()
    {
        if (is_array($this->getRoot())) {
            return implode('/', array_map('urlencode', $this->getRoot()));
        }
    }
}
