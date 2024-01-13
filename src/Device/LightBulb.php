<?php

declare(strict_types=1);

/**
 * Copyright (c) 2024 Benjamin Fahl
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/WebProject-xyz/ikea-tradfri-php
 */

namespace IKEA\Tradfri\Device;

use IKEA\Tradfri\Exception\RuntimeException;
use IKEA\Tradfri\Traits\ProvidesBrightness;
use IKEA\Tradfri\Traits\ProvidesColor;
use IKEA\Tradfri\Traits\ProvidesState;

/**
 * @final
 */
class LightBulb extends Device implements SwitchableInterface
{
    use ProvidesBrightness;
    use ProvidesColor;
    use ProvidesState;

    /**
     * @throws RuntimeException
     */
    public function switchOff(): bool
    {
        if (true === $this->getService()->off($this)) {
            $this->setState(false);

            return true;
        }

        throw new RuntimeException('switch OFF failed');
    }

    /**
     * @throws RuntimeException
     */
    public function switchOn(): bool
    {
        if (true === $this->getService()->on($this)) {
            $this->setState(true);

            return true;
        }

        throw new RuntimeException('switch ON failed');
    }

    /**
     * @throws RuntimeException
     */
    public function dim(int $levelInPercent): bool
    {
        if ($this->getService()->dim($this, $levelInPercent)) {
            $this->setState(0 < $levelInPercent);
            $this->setBrightnessLevel($levelInPercent);

            return true;
        }

        return false;
    }
}
