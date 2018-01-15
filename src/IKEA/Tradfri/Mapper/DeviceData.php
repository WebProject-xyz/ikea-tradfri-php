<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Mapper;

use IKEA\Tradfri\Collection\AbstractCollection;
use IKEA\Tradfri\Collection\Devices;
use IKEA\Tradfri\Device\Device;
use IKEA\Tradfri\Device\Dimmer;
use IKEA\Tradfri\Device\Lightbulb;
use IKEA\Tradfri\Device\MotionSensor;
use IKEA\Tradfri\Device\Remote;
use IKEA\Tradfri\Exception\RuntimeException;
use IKEA\Tradfri\Exception\TypeException;
use IKEA\Tradfri\Helper\CoapCommandKeys;
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
    public function map(ServiceInterface $service, array $devices): AbstractCollection
    {
        if (\count($devices) > 0) {
            $collection = new Devices();
            foreach ($devices as $device) {
                if (false === $this->_isValidData($device)) {
                    continue;
                }
                $id = (int) $device->{CoapCommandKeys::KEY_ID};

                try {
                    $model = $this->_getModel($id, $device, $service);
                } catch (TypeException $e) {
                    // todo add logger
                    continue;
                }

                $model->setName($device->{CoapCommandKeys::KEY_NAME});
                $model->setManufacturer($device->{CoapCommandKeys::KEY_DATA}->{CoapCommandKeys::KEY_MANUFACTURER});
                $model->setVersion($device->{CoapCommandKeys::KEY_DATA}->{CoapCommandKeys::KEY_VERSION});

                if ($model instanceof Lightbulb) {
                    $model->setBrightness($device->{CoapCommandKeys::KEY_DEVICE_DATA}[0]->{CoapCommandKeys::KEY_DIMMER});
                    $model->setState((bool) $device->{CoapCommandKeys::KEY_DEVICE_DATA}[0]->{CoapCommandKeys::KEY_ONOFF});
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
     * @param $device
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return bool
     */
    protected function _isValidData($device): bool
    {
        try {
            switch (false) {
                case \is_object($device):
                    throw new TypeException('device is no object');
                    break;
                case isset($device->{CoapCommandKeys::KEY_ID}):
                    throw new RuntimeException('attribute missing ('.CoapCommandKeys::KEY_ID);
                    break;
                case isset($device->{CoapCommandKeys::KEY_DATA}, $device->{CoapCommandKeys::KEY_DATA}->{CoapCommandKeys::KEY_TYPE}):
                    throw new RuntimeException('attribute missing type key');
                    break;
                case isset($device->{CoapCommandKeys::KEY_DATA}, $device->{CoapCommandKeys::KEY_DATA}->{CoapCommandKeys::KEY_MANUFACTURER}):
                    throw new RuntimeException('attribute missing type manufacturer');
                    break;
                case isset($device->{CoapCommandKeys::KEY_DATA}, $device->{CoapCommandKeys::KEY_DATA}->{CoapCommandKeys::KEY_VERSION}):
                    throw new RuntimeException('attribute missing type version');
                    break;
                // only for light bulbs (optional)
                case isset($device->{CoapCommandKeys::KEY_DEVICE_DATA}[0]):
                case isset($device->{CoapCommandKeys::KEY_DEVICE_DATA}[0]->{CoapCommandKeys::KEY_DIMMER}):
                case isset($device->{CoapCommandKeys::KEY_DEVICE_DATA}[0]->{CoapCommandKeys::KEY_ONOFF}):
                default:
                    return true;
            }
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Get model from device object.
     *
     * @param int              $id
     * @param \stdClass        $device
     * @param ServiceInterface $service
     *
     * @throws \IKEA\Tradfri\Exception\TypeException
     * @throws TypeException
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return Device|Lightbulb|Remote|MotionSensor
     */
    protected function _getModel(int $id, \stdClass $device, ServiceInterface $service)
    {
        if (isset($device->{CoapCommandKeys::KEY_DATA}->{CoapCommandKeys::KEY_TYPE})) {
            $type = $device->{CoapCommandKeys::KEY_DATA}->{CoapCommandKeys::KEY_TYPE};
            switch ($type) {
                case Device::TYPE_BLUB_E27_W:
                case Device::TYPE_BLUB_E27_WS:
                case Device::TYPE_BLUB_GU10:
                    $model = new Lightbulb($id, $type);
                    break;
                case Device::TYPE_MOTION_SENSOR:
                    $model = new MotionSensor($id, $type);
                    break;
                case Device::TYPE_REMOTE_CONTROL:
                    $model = new Remote($id, $type);
                    break;
                case Device::TYPE_DIMMER:
                    $model = new Dimmer($id, $type);
                    break;
                default:
                    throw new TypeException('invalid type: '.$type);
            }

            return $model->setType($type)->setService($service);
        }

        throw new TypeException('invalid object');
    }
}
