<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Command\Coap;

/**
 * Receiver is specific service with its own contract and can be only concrete.
 */
class Receiver
{
    public const COAP_COMMAND = '%s invalid %s';
    /**
     * @var string
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
     * @var string[]
     */
    protected $_output = [];

    /**
     * Put constructor.
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
     */
    final public function getCommand() : string
    {
        return $this->_getUri()
            .$this->_getInjectCommand()
            .' "'
            .$this->_getClientUri()
            .'"';
    }

    /**
     * Get command uri.
     */
    protected function _getUri() : string
    {
        return \sprintf(
            self::COAP_COMMAND,
            $this->_getUsername(),
            $this->_getApiKey()
        );
    }

    /**
     * Get Username.
     */
    protected function _getUsername() : string
    {
        return $this->_username;
    }

    /**
     * Get ApiKey.
     */
    protected function _getApiKey() : string
    {
        return $this->_apiKey;
    }

    /**
     * Get InjectCommand.
     */
    protected function _getInjectCommand() : string
    {
        return $this->_injectCommand;
    }

    /**
     * Get coap client uri.
     */
    protected function _getClientUri() : string
    {
        return 'coaps://'.$this->_getIpAddress().':5684/'
            .$this->_getRequestType();
    }

    /**
     * Get IpAddress.
     */
    protected function _getIpAddress() : string
    {
        return $this->_ipAddress;
    }

    /**
     * Get Request type.
     */
    protected function _getRequestType() : string
    {
        return $this->_requestType;
    }

    /**
     * Set RequestType.
     */
    public function setRequestType(string $requestType) : self
    {
        $this->_requestType = $requestType;

        return $this;
    }

    public function sendRequest() : string
    {
        // send command to gateway
        return \implode("\n", $this->_output);
    }
}
