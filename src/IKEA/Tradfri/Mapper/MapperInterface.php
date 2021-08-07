<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Mapper;

use IKEA\Tradfri\Collection\AbstractCollection;
use IKEA\Tradfri\Collection\Devices;
use IKEA\Tradfri\Collection\Groups;
use IKEA\Tradfri\Service\Api;
use IKEA\Tradfri\Service\ServiceInterface;

interface MapperInterface
{
    /**
     * @param Api|ServiceInterface $service
     *
     * @phpstan-return Devices|Groups
     */
    public function map(
        ServiceInterface $service,
        array $dataItems
    ): AbstractCollection;
}
