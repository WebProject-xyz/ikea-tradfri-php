<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Device;

use IKEA\Tradfri\Exception\RuntimeException;
use IKEA\Tradfri\Traits\ProvidesBrightness;
use IKEA\Tradfri\Traits\ProvidesColor;
use IKEA\Tradfri\Traits\ProvidesState;

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
    public function dim(int $level): bool
    {
        return $this->getService()->dim($this, $level);
    }
}
