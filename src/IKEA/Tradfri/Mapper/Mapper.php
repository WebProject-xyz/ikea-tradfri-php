<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Mapper;

use stdClass;

/**
 * Class Mapper.
 */
abstract class Mapper implements MapperInterface
{
    /**
     * Validate device data from api.
     *
     * @param stdClass|null $device
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    abstract protected function _isValidData($device): bool;
}
