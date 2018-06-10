<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Command\Light;

use IKEA\Tradfri\Command\Put;

/**
 * Class ChangeState.
 */
class ChangeState extends Put
{
    /**
     * Build command from coap command
     *        // todo rethink this.
     *
     * @return string
     */
    protected function _buildCommand(): string
    {
        // todo rethink this

        return 'ok';
    }
}
