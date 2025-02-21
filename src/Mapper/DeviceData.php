<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Benjamin Fahl
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
use IKEA\Tradfri\Device\Helper\Type;
use IKEA\Tradfri\Device\LightBulb;
use IKEA\Tradfri\Device\MotionSensor;
use IKEA\Tradfri\Device\Remote;
use IKEA\Tradfri\Device\RollerBlind;
use IKEA\Tradfri\Dto\CoapResponse\DeviceDto;
use IKEA\Tradfri\Exception\RuntimeException;
use IKEA\Tradfri\Service\ServiceInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * @template T of Devices|\IKEA\Tradfri\Collection\LightBulbs
 *
 * @template-implements MapperInterface<Devices>
 */
final class DeviceData implements LoggerAwareInterface, MapperInterface
{
    use LoggerAwareTrait;

    /**
     * @throws RuntimeException
     *
     * @phpstan-param T $collection
     * @phpstan-param DeviceDto[] $dataItems
     *
     * @phpstan-return T
     */
    public function map(
        ServiceInterface $service,
        iterable $dataItems,
        AbstractCollection $collection = new Devices(),
    ): AbstractCollection {
        foreach ($dataItems as $device) {
            if (!$device instanceof DeviceDto) {
                $this->logger?->warning('invalid device detected - skipped', ['device' => \serialize($device), 'type' => \get_debug_type($device)]);
                continue;
            }

            $model = $this->getModel(
                $device,
            );
            $model->setService($service);

            $this->setDeviceAttributes($model, $device);

            if ($model instanceof LightBulb) {
                $this->setLightBlubAttributes($model, $device);
            }

            if ($model instanceof RollerBlind) {
                $this->_setLightRollerBlindAttributes($model, $device);
            }

            $collection->set($model->getId(), $model);
        }

        return $collection;
    }

    /**
     * @throws RuntimeException
     *
     * @return Device|LightBulb|MotionSensor|Remote
     */
    private function getModel(DeviceDto $device): Device
    {
        return (new Type())
            ->buildFrom(
                $device->getDeviceInfo()->getType(),
                $device->getId(),
            );
    }

    private function setLightBlubAttributes(
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

    private function _setLightRollerBlindAttributes(
        RollerBlind $model,
        DeviceDto $device,
    ): void {
        if ($device->getBlindControlDto()) {
            $model->setDarkenedState(
                (int) $device->getBlindControlDto()[0]?->getState(),
            );
        }
    }

    private function setDeviceAttributes(Device $model, DeviceDto $device): void
    {
        $model->setName($device->getName());
        $model->setManufacturer($device->getDeviceInfo()->getManufacturer());
        $model->setVersion($device->getDeviceInfo()->getVersion());
    }
}
