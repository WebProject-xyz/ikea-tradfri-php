<?php

declare(strict_types=1);

/**
 * Copyright (c) 2024 Benjamin Fahl
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/WebProject-xyz/ikea-tradfri-php
 */

namespace IKEA\Tradfri\Mapper;

use IKEA\Tradfri\Collection\Devices;
use IKEA\Tradfri\Command\Coap\Keys as AttributeKeys;
use IKEA\Tradfri\Device\Device;
use IKEA\Tradfri\Device\Helper\Type;
use IKEA\Tradfri\Device\LightBulb;
use IKEA\Tradfri\Device\MotionSensor;
use IKEA\Tradfri\Device\Remote;
use IKEA\Tradfri\Device\RollerBlind;
use IKEA\Tradfri\Dto\CoapResponse\DeviceDto;
use IKEA\Tradfri\Exception\RuntimeException;
use IKEA\Tradfri\Service\ServiceInterface;

/**
 * @template T of Devices
 *
 * @template-implements MapperInterface<Devices>
 */
final class DeviceData implements MapperInterface
{
    /**
     * @throws RuntimeException
     *
     * @phpstan-param Devices $collection
     *
     * @phpstan-return Devices
     */
    public function map(
        ServiceInterface $service,
        array $dataItems,
        \IKEA\Tradfri\Collection\AbstractCollection $collection = new Devices(),
    ): \IKEA\Tradfri\Collection\AbstractCollection {
        if (\count($dataItems) > 0) {
            foreach ($dataItems as $device) {
                if (false === $this->isValidData($device)) {
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

        throw new RuntimeException('no data');
    }

    /**
     * @throws RuntimeException
     */
    public function isValidData(mixed $device): bool
    {
        if ($device instanceof DeviceDto) {
            return true;
        }

        return (new \IKEA\Tradfri\Validator\Device\Data())
            ->isValid($device);
    }

    /**
     * @throws RuntimeException
     *
     * @return Device|LightBulb|MotionSensor|Remote
     */
    private function getModel(object $device): Device
    {
        if ($device instanceof DeviceDto) {
            $idAttribute   = $device->getId();
            $typeAttribute = $device->getDeviceInfo()->getType();
        } else {
            // fallback
            $idAttribute   = $this->getDeviceId($device);
            $typeAttribute = $this->getDeviceTypeAttribute($device);
        }

        return (new Type())
            ->buildFrom(
                $typeAttribute,
                $idAttribute,
            );
    }

    private function getDeviceId(\stdClass $device): int
    {
        return (int) $device->{AttributeKeys::ATTR_ID};
    }

    private function setLightBlubAttributes(
        LightBulb $model,
        object $device,
    ): void {
        if ($device instanceof DeviceDto) {
            $brightness = 0;
            $color      = '';
            $state      = true;
        } else {
            $brightness = $device
                ->{AttributeKeys::ATTR_LIGHT_CONTROL}[0]
                ->{AttributeKeys::ATTR_LIGHT_DIMMER};

            $color = $device
                    ->{AttributeKeys::ATTR_LIGHT_CONTROL}[0]
                    ->{AttributeKeys::ATTR_LIGHT_COLOR_HEX} ?? '';

            $state = (bool) $device
                ->{AttributeKeys::ATTR_LIGHT_CONTROL}[0]
                ->{AttributeKeys::ATTR_LIGHT_STATE};
        }

        $model->setColor($color);
        $model->setBrightness($brightness);
        $model->setState($state);
    }

    private function _setLightRollerBlindAttributes(
        RollerBlind $model,
        \stdClass $device,
    ): void {
        $model->setDarkenedState(
            (int) $device
                ->{AttributeKeys::ATTR_FYRTUR_CONTROL}[0]
                ->{AttributeKeys::ATTR_FYRTUR_STATE},
        );
    }

    private function setDeviceAttributes(Device $model, object $device): void
    {
        if ($device instanceof DeviceDto) {
            $name         = $device->getName();
            $manufacturer = $device->getDeviceInfo()->getManufacturer();
            $version      = $device->getDeviceInfo()->getVersion();
        } else {
            $name         = $device->{AttributeKeys::ATTR_NAME};
            $manufacturer = $device
                ->{AttributeKeys::ATTR_DEVICE_INFO}
                ->{AttributeKeys::ATTR_DEVICE_INFO_MANUFACTURER};
            $version = $device
                ->{AttributeKeys::ATTR_DEVICE_INFO}
                ->{AttributeKeys::ATTR_DEVICE_VERSION};
        }

        $model->setName($name);
        $model->setManufacturer($manufacturer);
        $model->setVersion($version);
    }

    /**
     * @deprecated
     */
    private function getDeviceTypeAttribute(\stdClass $device): string
    {
        return $device
            ->{AttributeKeys::ATTR_DEVICE_INFO}
            ->{AttributeKeys::ATTR_DEVICE_INFO_TYPE};
    }
}
