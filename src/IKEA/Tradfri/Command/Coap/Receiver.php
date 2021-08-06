<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Command\Coap;

/**
 * Receiver is specific service with its own contract and can be only concrete.
 */
class Receiver
{
    public const COAP_COMMAND = '%s invalid %s';

    protected string $requestType;

    protected string $injectCommand;

    protected string $ipAddress;

    protected string $username;

    protected string $apiKey;

    /**
     * @var string[]
     */
    protected array $output = [];

    public function __construct(
        string $gatewayAddress,
        string $username,
        string $apiKey
    ) {
        $this->ipAddress = $gatewayAddress;
        $this->username  = $username;
        $this->apiKey    = $apiKey;
    }

    /**
     * Get command.
     */
    final public function getCommand(): string
    {
        return $this->_getUri()
            . $this->_getInjectCommand()
            . ' "'
            . $this->_getClientUri()
            . '"';
    }

    /**
     * Get command uri.
     */
    protected function _getUri(): string
    {
        return sprintf(
            self::COAP_COMMAND,
            $this->_getUsername(),
            $this->_getApiKey()
        );
    }

    /**
     * Get Username.
     */
    protected function _getUsername(): string
    {
        return $this->username;
    }

    /**
     * Get ApiKey.
     */
    protected function _getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * Get InjectCommand.
     */
    protected function _getInjectCommand(): string
    {
        return $this->injectCommand;
    }

    /**
     * Get coap client uri.
     */
    protected function _getClientUri(): string
    {
        return 'coaps://' . $this->_getIpAddress() . ':5684/'
            . $this->_getRequestType();
    }

    /**
     * Get IpAddress.
     */
    protected function _getIpAddress(): string
    {
        return $this->ipAddress;
    }

    /**
     * Get Request type.
     */
    protected function _getRequestType(): string
    {
        return $this->requestType;
    }

    /**
     * Set RequestType.
     */
    public function setRequestType(string $requestType): self
    {
        $this->requestType = $requestType;

        return $this;
    }

    public function sendRequest(): string
    {
        // send command to gateway
        return implode("\n", $this->output);
    }
}
