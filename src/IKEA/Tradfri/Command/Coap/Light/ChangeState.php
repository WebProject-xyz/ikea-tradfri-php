<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Command\Coap\Light;

use IKEA\Tradfri\Command\Put;

class ChangeState extends Put
{
    /**
     * Build command from coap command.
     */
    protected function buildCommand(): string
    {
        // todo rethink this

        return 'ok';
    }
}
