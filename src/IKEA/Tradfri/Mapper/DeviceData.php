<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Mapper;

use IKEA\Tradfri\Collection\AbstractCollection;
use IKEA\Tradfri\Collection\Devices;
use IKEA\Tradfri\Command\Coap\Keys as AttributeKeys;
use IKEA\Tradfri\Device\Device;
use IKEA\Tradfri\Device\Dimmer;
use IKEA\Tradfri\Device\Lightbulb;
use IKEA\Tradfri\Device\MotionSensor;
use IKEA\Tradfri\Device\Remote;
use IKEA\Tradfri\Exception\RuntimeException;
use IKEA\Tradfri\Exception\TypeException;
use IKEA\Tradfri\Service\ServiceInterface;

/**
 * Class DeviceData.
 */
class DeviceData extends Mapper
{
    /**
     * Map data to Lightbulbs.
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
                    $this->_getDeviceId($device),
                    $device,
                    $service
                );

                $this->_setDeviceAttributes($model, $device);

                if ($model instanceof Lightbulb) {
                    $this->_setLightBlubAttributes($model, $device);
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
     * @param int              $deviceId
     * @param \stdClass        $device
     * @param ServiceInterface $service
     *
     * @throws \IKEA\Tradfri\Exception\TypeException
     * @throws TypeException
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return Device|Lightbulb|MotionSensor|Remote
     */
    protected function _getModel(
        int $deviceId,
        \stdClass $device,
        ServiceInterface $service
    ) {
        $type = $device
            ->{AttributeKeys::ATTR_DEVICE_INFO}
            ->{AttributeKeys::ATTR_DEVICE_INFO_TYPE};

        switch ($type) {
          //  case AttributeKeys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_W:
           // case AttributeKeys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_WS:
            case AttributeKeys::ATTR_DEVICE_INFO_TYPE_BLUB_GU10_WS:
            case AttributeKeys::ATTR_DEVICE_INFO_TYPE_BLUB_GU10_W:
                $model = new Lightbulb($deviceId, $type);

                break;
            case AttributeKeys::ATTR_DEVICE_INFO_TYPE_MOTION_SENSOR:
                $model = new MotionSensor($deviceId);

                break;
            case AttributeKeys::ATTR_DEVICE_INFO_TYPE_REMOTE_CONTROL:
                $model = new Remote($deviceId);

                break;
            case AttributeKeys::ATTR_DEVICE_INFO_TYPE_DIMMER:
                $model = new Dimmer($deviceId);

                break;
            default:
                throw new TypeException('invalid type: '.$type);
        }

        return $model->setType($type)->setService($service);
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
     * Set Lightbulb attributes.
     *
     * @param Lightbulb $model
     * @param \stdClass $device
     */
    protected function _setLightBlubAttributes(
        Lightbulb $model,
        \stdClass $device
    ) {
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
     * Set Device attributes.
     *
     * @param Device $model
     * @param \stdClass$device
     */
    protected function _setDeviceAttributes(Device $model, \stdClass $device)
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
}
