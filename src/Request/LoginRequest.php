<?php

namespace DMT\WebservicesNl\Client\Request;

use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class LoginRequest
 *
 * Logs the user in using username and password
 *
 * @JMS\AccessType("public_method")
 * @JMS\XmlRoot("LoginRequest", namespace="http://www.webservices.nl/soap/")
 */
class LoginRequest implements RequestInterface
{
    /**
     * @Assert\NotBlank(message="Login requires an username")
     *
     * @JMS\SerializedName("username")
     * @JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     *
     * @var string
     */
    protected $username;

    /**
     * @Assert\NotBlank(message="Login requires a password")
     *
     * @JMS\SerializedName("password")
     * @JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     *
     * @var string
     */
    protected $password;

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}
