<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Command;

use IKEA\Tradfri\Client\Client;

/**
 * Class CommandAbstract.
 */
abstract class CommandAbstract
{
    const COAP_COMMAND_GET = 'coap-client -m get -u "%s" -k "%s"';
    const COAP_COMMAND_PUT = 'coap-client -m put -u "%s" -k "%s"';
    const COAP_COMMAND_POST = 'coap-client -m post -u "%s" -k "%s"';

    /**
     * @var Client
     */
    protected $_client;

    /**
     * Get Client.
     *
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->_client;
    }

    /**
     * Set Client.
     *
     * @param Client $client
     *
     * @return CommandAbstract
     */
    public function setClient(Client $client): self
    {
        $this->_client = $client;

        return $this;
    }

    /**
     * Execute command.
     *
     * @return $this
     */
    abstract public function execute(): self;
}
