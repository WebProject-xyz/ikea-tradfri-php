<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Mapper;

/**
 * Class Mapper.
 */
abstract class Mapper implements MapperInterface
{
    /**
     * Validate device data from api.
     *
     * @param null|\stdClass $device
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return bool
     */
    abstract protected function _isValidData($device): bool;
}
