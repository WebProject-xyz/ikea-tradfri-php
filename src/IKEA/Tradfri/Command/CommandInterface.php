<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Command;

/**
 * Interface CommandInterface.
 */
interface CommandInterface
{
    /**
     * Echo command.
     *
     * @return string
     */
    public function __toString(): string;
}
