<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Device;

use IKEA\Tradfri\Exception\RuntimeException;
use IKEA\Tradfri\Traits\ProvidesBrightness;
use IKEA\Tradfri\Traits\ProvidesColor;
use IKEA\Tradfri\Traits\ProvidesState;

/**
 * Class Lamp.
 */
class LightBulb extends Device implements SwitchableInterface
{
    use ProvidesBrightness;
    use ProvidesColor;
    use ProvidesState;

    /**
     * Switch off.
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return bool
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
     * Switch off.
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return bool
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
     * Dim Light.
     *
     * @param int $level
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return bool
     */
    public function dim(int $level): bool
    {
        return $this->getService()->dim($this, $level);
    }
}
