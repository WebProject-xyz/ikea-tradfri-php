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
     * Execute command.
     *
     * @return $this
     */
    abstract public function execute(): self;

    /**
     * Get Client
     *
     * @return mixed
     */
    public function getClient()
    {
        return $this->_client;
    }

    /**
     * Set Client
     *
     * @param mixed $client
     *
     * @return CommandAbstract
     */
    public function setClient(mixed $client)
    {
        $this->_client = $client;

        return $this;
    }
}
