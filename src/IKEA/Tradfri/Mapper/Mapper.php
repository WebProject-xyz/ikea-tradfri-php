<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Mapper;

use stdClass;

abstract class Mapper implements MapperInterface
{
    /**
     * @param stdClass|null $device
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    abstract protected function isValidData($device): bool;
}
