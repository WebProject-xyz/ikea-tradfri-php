<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Mapper;

use IKEA\Tradfri\Collection\AbstractCollection;
use IKEA\Tradfri\Collection\Devices;
use IKEA\Tradfri\Command\Coap\Keys as AttributeKeys;
use IKEA\Tradfri\Device\Device;
use IKEA\Tradfri\Device\Dimmer;
use IKEA\Tradfri\Device\Floalt;
use IKEA\Tradfri\Device\Helper\Type;
use IKEA\Tradfri\Device\LightBulb;
use IKEA\Tradfri\Device\MotionSensor;
use IKEA\Tradfri\Device\OpenCloseRemote;
use IKEA\Tradfri\Device\Remote;
use IKEA\Tradfri\Device\Repeater;
use IKEA\Tradfri\Device\RollerBlind;
use IKEA\Tradfri\Device\Unknown;
use IKEA\Tradfri\Exception\RuntimeException;
use IKEA\Tradfri\Service\ServiceInterface;
use stdClass;
use function count;

class DeviceData extends Mapper
{
    /**
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return Devices
     */
    public function map(
        ServiceInterface $service,
        array $devices
    ): AbstractCollection {
        if (count($devices) > 0) {
            $collection = new Devices();
            foreach ($devices as $device) {
                if (false === $this->isValidData($device)) {
                    continue;
                }

                $model = $this->getModel(
                    $device
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
     * @param stdClass|null $device
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    protected function isValidData($device): bool
    {
        $validator = new \IKEA\Tradfri\Validator\Device\Data();

        return $validator->isValid($device);
    }

    /**
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return Device|LightBulb|MotionSensor|Remote
     */
    protected function getModel(stdClass $device)
    {
        $typeAttribute    = $this->getDeviceTypeAttribute($device);
        $deviceTypeHelper = new Type();

        switch (true) {
            case $deviceTypeHelper->isLightBulb($typeAttribute):
                $modelName = LightBulb::class;

                break;
            case $deviceTypeHelper->isMotionSensor($typeAttribute):
                $modelName = MotionSensor::class;

                break;
            case $deviceTypeHelper->isRemote($typeAttribute):
                $modelName = Remote::class;

                break;
            case $deviceTypeHelper->isDimmer($typeAttribute):
                $modelName = Dimmer::class;

                break;
            case $deviceTypeHelper->isFloalt($typeAttribute):
                $modelName = Floalt::class;

                break;
            case $deviceTypeHelper->isOpenCloseRemote($typeAttribute):
                $modelName = OpenCloseRemote::class;

                break;
            case $deviceTypeHelper->isRepeater($typeAttribute):
                $modelName = Repeater::class;

                break;
            case $deviceTypeHelper->isRollerBlind($typeAttribute):
                $modelName = RollerBlind::class;

                break;
            case false === $deviceTypeHelper->isKnownDeviceType($typeAttribute):
            default:
                $modelName = Unknown::class;
        }

        return new $modelName(
            $this->getDeviceId($device),
            $typeAttribute
        );
    }

    protected function getDeviceId(stdClass $device): int
    {
        return (int) $device->{AttributeKeys::ATTR_ID};
    }

    protected function setLightBlubAttributes(
        LightBulb $model,
        stdClass $device
    ): void {
        $model->setBrightness(
            $device
                ->{AttributeKeys::ATTR_LIGHT_CONTROL}[0]
                ->{AttributeKeys::ATTR_LIGHT_DIMMER}
        );

        $model->setColor(
            $device
                ->{AttributeKeys::ATTR_LIGHT_CONTROL}[0]
                ->{AttributeKeys::ATTR_LIGHT_COLOR_HEX} ?? ''
        );

        $model->setState(
            (bool) $device
                ->{AttributeKeys::ATTR_LIGHT_CONTROL}[0]
                ->{AttributeKeys::ATTR_LIGHT_STATE}
        );
    }

    protected function _setLightRollerBlindAttributes(
        RollerBlind $model,
        stdClass $device
    ): void {
        $model->setDarkenedState(
            (int) $device
                ->{AttributeKeys::ATTR_FYRTUR_CONTROL}[0]
                ->{AttributeKeys::ATTR_FYRTUR_STATE}
        );
    }

    protected function setDeviceAttributes(Device $model, stdClass $device): void
    {
        $model->setName($device->{AttributeKeys::ATTR_NAME});

        $model->setManufacturer(
            $device
                ->{AttributeKeys::ATTR_DEVICE_INFO}
                ->{AttributeKeys::ATTR_DEVICE_INFO_MANUFACTURER}
        );

        $model->setVersion(
            $device
                ->{AttributeKeys::ATTR_DEVICE_INFO}
                ->{AttributeKeys::ATTR_DEVICE_VERSION}
        );
    }

    protected function getDeviceTypeAttribute(stdClass $device): string
    {
        return $device
            ->{AttributeKeys::ATTR_DEVICE_INFO}
            ->{AttributeKeys::ATTR_DEVICE_INFO_TYPE};
    }
}
