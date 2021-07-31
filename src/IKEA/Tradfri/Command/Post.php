<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Command;

/**
 * Class Get.
 */
class Post extends AbstractCommand
{
    public const COAP_COMMAND = 'coap-client -m post -u "%s" -k "%s"';

    /**
     * Build command from coap command.
     */
    protected function _buildCommand() : string
    {
        // @TODO: Implement _buildCommand() method.
    }
}
