<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Command;

/**
 * Interface CommandInterface.
 */
interface CommandInterface
{
    /**
     * this is the most important method in the Command pattern,
     * The Receiver goes in the constructor.
     */
    public function execute();
}
