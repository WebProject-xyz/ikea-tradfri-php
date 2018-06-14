<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Command;

/**
 * Class AbstractCommand.
 */
abstract class AbstractCommand implements CommandInterface
{
    /**
     * @var
     */
    protected $_requestType;

    /**
     * @var string
     */
    protected $_injectCommand;

    /**
     * @var string
     */
    protected $_ipAddress;

    /**
     * @var string
     */
    protected $_username;

    /**
     * @var string
     */
    protected $_apiKey;

    /**
     * Put constructor.
     *
     * @param string $gatewayAddress
     * @param string $username
     * @param string $apiKey
     */
    public function __construct(
        string $gatewayAddress,
        string $username,
        string $apiKey
    ) {
        $this->_ipAddress = $gatewayAddress;
        $this->_username = $username;
        $this->_apiKey = $apiKey;
    }

    /**
     * Get command.
     *
     * @return string
     */
    final public function getCommand(): string
    {
        return $this->_getUri()
            .$this->_getInjectCommand()
            .' "'
            .$this->_getClientUri()
            .'"';
    }

    /**
     * Get command uri.
     *
     * @return string
     */
    protected function _getUri(): string
    {
        return \sprintf(
            self::COAP_COMMAND, $this->_getUsername(), $this->_getApiKey()
        );
    }

    /**
     * Get Username.
     *
     * @return string
     */
    protected function _getUsername(): string
    {
        return $this->_username;
    }

    /**
     * Get ApiKey.
     *
     * @return string
     */
    protected function _getApiKey(): string
    {
        return $this->_apiKey;
    }

    /**
     * Get InjectCommand.
     *
     * @return string
     */
    protected function _getInjectCommand()
    {
        return $this->_injectCommand;
    }

    /**
     * Get coap client uri.
     *
     * @return string
     */
    protected function _getClientUri(): string
    {
        return 'coaps://'.$this->_getIpAddress().':5684/'
            .$this->_getRequestType();
    }

    /**
     * Get IpAddress.
     *
     * @return string
     */
    protected function _getIpAddress(): string
    {
        return $this->_ipAddress;
    }

    /**
     * Get Request type.
     *
     * @return string
     */
    protected function _getRequestType()
    {
        return $this->_requestType;
    }

    /**
     * Set RequestType.
     *
     * @param string $requestType
     *
     * @return Put
     */
    public function setRequestType(string $requestType): self
    {
        $this->_requestType = $requestType;

        return $this;
    }

    /**
     * Build command from coap command.
     *
     * @return string
     */
    abstract protected function _buildCommand(): string;

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->_buildCommand();
    }
}
