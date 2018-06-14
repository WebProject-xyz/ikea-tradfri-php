<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Command\Coap\Light;

use IKEA\Tradfri\Command\Put;

/**
 * Class ChangeState.
 */
class ChangeState extends Put
{
    /**
     * Build command from coap command.
     *
     * @return string
     */
    protected function _buildCommand(): string
    {
        // todo rethink this

        return 'ok';
    }
}
