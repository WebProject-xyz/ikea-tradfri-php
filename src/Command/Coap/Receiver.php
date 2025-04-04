<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Benjamin Fahl
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/WebProject-xyz/ikea-tradfri-php
 */

namespace IKEA\Tradfri\Command\Coap;

/**
 * Receiver is specific service with its own contract and can be only concrete.
 */
final class Receiver
{
    final public const string COAP_COMMAND = '%s invalid %s';
    private string $requestType;
    private string $injectCommand;

    /**
     * @var list<string>
     */
    private array $output = [];

    public function __construct(
        private readonly string $ipAddress,
        private readonly string $username,
        #[\SensitiveParameter()]
        private readonly string $apiKey,
    ) {
    }

    public function getCommand(): string
    {
        return $this->getUri()
            . $this->getInjectCommand()
            . ' "'
            . $this->getClientUri()
            . '"';
    }

    public function setRequestType(string $requestType): self
    {
        $this->requestType = $requestType;

        return $this;
    }

    public function sendRequest(): string
    {
        // send command to gateway
        return \implode("\n", $this->output);
    }

    public function setInjectCommand(string $injectCommand): void
    {
        $this->injectCommand = $injectCommand;
    }

    private function getUri(): string
    {
        return \sprintf(
            self::COAP_COMMAND,
            $this->username,
            $this->apiKey,
        );
    }

    private function getInjectCommand(): string
    {
        return $this->injectCommand;
    }

    private function getClientUri(): string
    {
        return 'coaps://' . $this->_getIpAddress() . ':5684/'
            . $this->requestType;
    }

    private function _getIpAddress(): string
    {
        return $this->ipAddress;
    }
}
