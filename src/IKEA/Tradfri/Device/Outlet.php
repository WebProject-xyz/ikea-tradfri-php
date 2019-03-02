<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Device;

use IKEA\Tradfri\Command\Coap\Keys;
use IKEA\Tradfri\Device\Feature\Switchable;

/**
 * Class Outlet.
 */
class Outlet extends Device implements Switchable
{
    /**
     * @var bool
     */
    protected $_state = false;

    /**
     * Outlet constructor.
     *
     * @param int $deviceId
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function __construct($deviceId)
    {
        parent::__construct(
            $deviceId,
            Keys::ATTR_DEVICE_INFO_TYPE_OUTLET
        );
    }

    /**
     * Get state as string.
     *
     * @return string
     */
    public function getState(): string
    {
        return $this->isOn() ? 'On' : 'Off';
    }

    /**
     * Set State.
     *
     * @param bool $state
     *
     * @return static
     */
    public function setState(bool $state)
    {
        $this->_state = $state;

        return $this;
    }

    /**
     * Get State.
     *
     * @return bool
     */
    public function isOn(): bool
    {
        return $this->_state;
    }
}
