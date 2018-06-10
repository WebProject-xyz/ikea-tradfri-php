<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Command;

/**
 * Class Get.
 */
class Post implements CommandInterface
{
    const COAP_COMMAND = 'coap-client -m post -u "%s" -k "%s"';

    /**
     * Execute command.
     *
     * @return bool
     */
    public function execute(): bool
    {
        // @TODO: Implement execute() method.
        // combine request and coap command and execute it with the client
    }

    /**
     * Echo command.
     *
     * @return string
     */
    public function __toString(): string
    {
        // @TODO: Implement __toString() method.
        return '';
    }
}
