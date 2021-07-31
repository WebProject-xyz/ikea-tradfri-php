<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Mapper;

use IKEA\Tradfri\Collection\AbstractCollection;
use IKEA\Tradfri\Collection\Devices;
use IKEA\Tradfri\Service\Api;
use IKEA\Tradfri\Service\ServiceInterface;

/**
 * Interface MapperInterface.
 */
interface MapperInterface
{
    /**
     * Map given data to models.
     *
     * @param Api|ServiceInterface $service
     *
     * @return AbstractCollection|Devices
     */
    public function map(
        ServiceInterface $service,
        array $dataItems
    ) : AbstractCollection;
}
