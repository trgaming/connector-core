<?php
namespace Jtl\Connector\Core\Exception;

class SerializerException extends \Exception
{
    const WRONG_ENDPOINT_FORMAT = 10;

    /**
     * @param string $endpoint
     * @return SerializerException
     */
    public static function wrongEndpointFormat(string $endpoint): SerializerException
    {
        return new static(sprintf('Endpoint "%s" has the wrong format', $endpoint), self::WRONG_ENDPOINT_FORMAT);
    }
}
