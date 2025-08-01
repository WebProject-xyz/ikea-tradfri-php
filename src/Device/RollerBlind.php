<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Benjamin Fahl.
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/WebProject-xyz/ikea-tradfri-php
 */

namespace IKEA\Tradfri\Device;

use IKEA\Tradfri\Traits\ProvidesDarkenedState;

final class RollerBlind extends Device
{
    use ProvidesDarkenedState;

    /**
     * Close Roller Blind.
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function close(): bool
    {
        return $this->setToPosition(100);
    }

    /**
     * Open Roller Blind.
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function open(): bool
    {
        return $this->setToPosition(0);
    }

    /**
     * Set Roller Blind to specific position.
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function setToPosition(int $level): bool
    {
        $wasSet = $this->getService()->setRollerBlindPosition($this, $level);
        if ($wasSet) {
            $this->setDarkenedState($level);
        }

        return $wasSet;
    }
}
