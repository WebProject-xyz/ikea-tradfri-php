<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Command;

/**
 * Class Get.
 */
class Post extends AbstractCommand
{
    const COAP_COMMAND = 'coap-client -m post -u "%s" -k "%s"';

    /**
     * Build command from coap command.
     *
     * @return string
     */
    protected function _buildCommand(): string
    {
        // @TODO: Implement _buildCommand() method.
    }
}
