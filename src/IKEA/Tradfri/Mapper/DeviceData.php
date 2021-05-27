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
use IKEA\Tradfri\Validator\Device\Data;

/**
 * Class DeviceData.
 */
class DeviceData extends Mapper
{
    /**
     * Map data to LightBulbs.
     *
     * @param ServiceInterface $service
     * @param array            $devices
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return Devices
     */
    public function map(
        ServiceInterface $service,
        array $devices
    ): AbstractCollection {
        if (\count($devices) > 0) {
            $collection = new Devices();
            foreach ($devices as $device) {
                if (false === $this->_isValidData($device)) {
                    continue;
                }

                $model = $this->_getModel(
                    $device
                );
                $model->setService($service);

                $this->_setDeviceAttributes($model, $device);

                if ($model instanceof LightBulb) {
                    $this->_setLightBlubAttributes($model, $device);
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
     * Validate device data from api.
     *
     * @param null|\stdClass $device
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return bool
     */
    protected function _isValidData($device): bool
    {
        $validator = new \IKEA\Tradfri\Validator\Device\Data();

        return $validator->isValid($device);
    }

    /**
     * Get model from device object.
     *
     * @param \stdClass $device
     *
     *@throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return Device|LightBulb|MotionSensor|Remote
     */
    protected function _getModel(\stdClass $device)
    {
        $deviceTypeHelper = new Type();
        $typeAttribute = $this->_getDeviceTypeAttribute($device);

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
            $this->_getDeviceId($device),
            $typeAttribute
        );
    }

    /**
     * Get Device id.
     *
     * @param \stdClass $device
     *
     * @return int
     */
    protected function _getDeviceId(\stdClass $device): int
    {
        return (int) $device->{AttributeKeys::ATTR_ID};
    }

    /**
     * Set LightBulb attributes.
     *
     * @param LightBulb $model
     * @param \stdClass $device
     */
    protected function _setLightBlubAttributes(
        LightBulb $model,
        \stdClass $device
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

    /**
     * Set LightBulb attributes.
     *
     * @param RollerBlind $model
     * @param \stdClass $device
     */
    protected function _setLightRollerBlindAttributes(
        RollerBlind $model,
        \stdClass $device
    ): void {
        $model->setDarkenedState(
            (int) $device
                ->{AttributeKeys::ATTR_FYRTUR_CONTROL}[0]
                ->{AttributeKeys::ATTR_FYRTUR_STATE}
        );
    }


    /**
     * Set Device attributes.
     *
     * @param Device $model
     * @param \stdClass$device
     */
    protected function _setDeviceAttributes(Device $model, \stdClass $device): void
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

    /**
     * Get Device Type Attribute.
     *
     * @param \stdClass $device
     *
     * @return mixed
     */
    protected function _getDeviceTypeAttribute(\stdClass $device): string
    {
        return $device
            ->{AttributeKeys::ATTR_DEVICE_INFO}
            ->{AttributeKeys::ATTR_DEVICE_INFO_TYPE};
    }
}
