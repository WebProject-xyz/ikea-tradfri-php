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

namespace IKEA\Tradfri\Mapper;

use IKEA\Tradfri\Collection\AbstractCollection;
use IKEA\Tradfri\Collection\Devices;
use IKEA\Tradfri\Device\Device;
use IKEA\Tradfri\Device\Feature\DeviceInterface;
use IKEA\Tradfri\Device\LightBulb;
use IKEA\Tradfri\Device\RollerBlind;
use IKEA\Tradfri\Dto\CoapResponse\DeviceDto;
use IKEA\Tradfri\Service\ServiceInterface;
use IKEA\Tradfri\Values\DeviceType;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * @template-implements MapperInterface<DeviceInterface, DeviceDto, Devices>
 */
final class DeviceData implements LoggerAwareInterface, MapperInterface
{
    use LoggerAwareTrait;

    /**
     * @template TOutput of Devices<DeviceInterface>
     *
     * @phpstan-param TOutput $collection
     *
     * @phpstan-return TOutput $collection
     */
    public function map(
        ServiceInterface $service,
        iterable $dataItems,
        AbstractCollection $collection,
    ): AbstractCollection {
        foreach ($dataItems as $device) {
            $model = DeviceType::initModel(
                deviceTypeValue: $device->getDeviceInfo()->getType(),
                id: $device->getId(),
                allowUnknown: true,
            )->setService($service);

            self::setDeviceAttributes($model, $device);

            if ($model instanceof LightBulb) {
                self::setLightBlubAttributes($model, $device);
            }

            if ($model instanceof RollerBlind) {
                self::_setLightRollerBlindAttributes($model, $device);
            }

            $collection->set($model->getId(), $model);
        }

        return $collection;
    }

    private static function setLightBlubAttributes(
        LightBulb $model,
        DeviceDto $device,
    ): void {
        $lightControl = $device->getLightControl();
        if ($lightControl instanceof \IKEA\Tradfri\Dto\CoapResponse\LightControlDto) {
            $model->setColor((string) $lightControl->getColorHex());
            $model->setBrightness($lightControl->getBrightness());
            $model->setState($lightControl->getState() > 0);
        }
    }

    private static function _setLightRollerBlindAttributes(
        RollerBlind $model,
        DeviceDto $device,
    ): void {
        if ($device->getBlindControlDto()) {
            $model->setDarkenedState(
                $device->getBlindControlDto()[0]->getState(),
            );
        }
    }

    private static function setDeviceAttributes(Device $model, DeviceDto $device): void
    {
        $model->setName($device->getName());
        $model->setManufacturer($device->getDeviceInfo()->getManufacturer());
        $model->setVersion($device->getDeviceInfo()->getVersion());
    }
}
