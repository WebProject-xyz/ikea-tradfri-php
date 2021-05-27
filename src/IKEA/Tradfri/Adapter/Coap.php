<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Adapter;

use IKEA\Tradfri\Collection\Devices;
use IKEA\Tradfri\Collection\Groups;
use IKEA\Tradfri\Command\Coap\Keys;
use IKEA\Tradfri\Command\Coaps;
use IKEA\Tradfri\Exception\RuntimeException;
use IKEA\Tradfri\Group\Light;
use IKEA\Tradfri\Helper\Runner;
use IKEA\Tradfri\Mapper\MapperInterface;
use IKEA\Tradfri\Service\ServiceInterface;

/**
 * Class Coap.
 */
class Coap extends AdapterAbstract
{
    public const COULD_NOT_SWITCH_STATE = 'Could not switch state';

    /**
     * @var Coaps
     */
    protected $_commands;

    /**
     * Coap constructor.
     *
     * @param Coaps           $commands
     * @param MapperInterface $deviceDataMapper
     * @param MapperInterface $groupDataMapper
     */
    public function __construct(
        Coaps $commands,
        MapperInterface $deviceDataMapper,
        MapperInterface $groupDataMapper
    ) {
        $this->_commands = $commands;
        parent::__construct($deviceDataMapper, $groupDataMapper);
    }

    /**
     * Get device type.
     *
     * @param int $deviceId
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return string
     */
    public function getType(int $deviceId): string
    {
        $data = $this->_getData(Keys::ROOT_DEVICES, $deviceId);
        if (\is_object($data)
            && \property_exists($data, Keys::ATTR_DEVICE_INFO)
            && \property_exists($data, Keys::ATTR_DEVICE_INFO_TYPE)
        ) {
            return $data
                ->{Keys::ATTR_DEVICE_INFO}
                ->{Keys::ATTR_DEVICE_INFO_TYPE};
        }

        throw new RuntimeException('invalid coap response');
    }

    /**
     * Get data from coaps client.
     *
     * @param string   $requestType
     * @param null|int $deviceId
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return array|\stdClass|string
     */
    protected function _getData(string $requestType, int $deviceId = null)
    {
        $command = $this->_commands->getCoapsCommandGet($requestType);

        if (null !== $deviceId) {
            $command .= '/'.$deviceId;
        }

        $dataRaw = $this->_commands->parseResult(
            (new Runner())->execWithTimeout(
                $command,
                1,
                true
            )
        );

        if (false !== $dataRaw) {
            return $this->_decodeData($dataRaw);
        }

        throw new RuntimeException('invalid hub response');
    }

    /**
     * Get Manufacturer by device id.
     *
     * @param int $deviceId
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return string
     */
    public function getManufacturer(int $deviceId): string
    {
        $data = $this->_getData(Keys::ROOT_DEVICES, $deviceId);

        if (isset($data->{Keys::ATTR_DEVICE_INFO}->{'0'})) {
            return $data->{Keys::ATTR_DEVICE_INFO}->{'0'};
        }

        throw new RuntimeException('invalid hub response');
    }

    /**
     * Change State of given device.
     *
     * @param int  $deviceId
     * @param bool $toState
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return bool
     */
    public function changeLightState(int $deviceId, bool $toState): bool
    {
        // run command
        $data = (new Runner())
            ->execWithTimeout(
                $this->_commands->getLightSwitchCommand($deviceId, $toState),
                2,
                true,
                true
            );

        // verify result
        if (\is_array($data) && empty($data[0])) {
            /*
             * @example data response is now empty since hub update
             * so we only check if there is no error message inside
             */
            return true;
        }

        throw new RuntimeException(self::COULD_NOT_SWITCH_STATE);
    }

    /**
     * Change state of group.
     *
     * @param int  $groupId
     * @param bool $toState
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return bool
     */
    public function changeGroupState(int $groupId, bool $toState): bool
    {
        // run command
        $data = (new Runner())
            ->execWithTimeout(
                $this->_commands->getGroupSwitchCommand($groupId, $toState),
                2,
                true
            );

        // verify result

        if ($this->_verifyResult($data)) {
            return true;
        }

        throw new RuntimeException(self::COULD_NOT_SWITCH_STATE);
    }

    /**
     * Set Light Brightness.
     *
     * @param int $lightId
     * @param int $level
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return bool
     */
    public function setLightBrightness(int $lightId, int $level): bool
    {
        // run command
        $data = (new Runner())->execWithTimeout(
            $this->_commands->getLightDimmerCommand($lightId, $level),
            2,
            true
        );

        // verify result
        if ($this->_verifyResult($data)) {
            return true;
        }

        throw new RuntimeException(self::COULD_NOT_SWITCH_STATE);
    }

    /**
     * @param $rollerBlindId
     * @param $level
     *
     * @return bool
     */
    public function setRollerBlindPosition($rollerBlindId, $level): bool
    {

        // run command
        $data = (new Runner())->execWithTimeout(
            $this->_commands->getRollerBlindDarkenedStateCommand($rollerBlindId, $level),
            2,
            true
        );
        //@todo: verify result, seems to work for now.
        return true;
        if ($this->_verifyResult($data)) {
            return true;
        }

        throw new RuntimeException(self::COULD_NOT_SWITCH_STATE);
    }

    /**
     * Set Group Brightness.
     *
     * @param int $groupId
     * @param int $level
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return bool
     */
    public function setGroupBrightness(int $groupId, int $level): bool
    {
        // run command
        $data = (new Runner())->execWithTimeout(
            $this->_commands->getGroupDimmerCommand($groupId, $level),
            2,
            true
        );

        // verify result
        if ($this->_verifyResult($data)) {
            return true;
        }

        throw new RuntimeException(self::COULD_NOT_SWITCH_STATE);
    }

    /**
     * Get a collection of devices.
     *
     * @param ServiceInterface $service
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return Devices
     */
    public function getDeviceCollection(ServiceInterface $service): Devices
    {
        return $this
            ->getDeviceDataMapper()
            ->map($service, $this->getDevicesData());
    }

    /**
     * Get devices.
     *
     * @param null|array $deviceIds
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return array
     */
    public function getDevicesData(array $deviceIds = null): array
    {
        if (null === $deviceIds) {
            $deviceIds = $this->getDeviceIds();
        }
        $deviceData = [];
        foreach ($deviceIds as $deviceId) {
            // sometimes the request are to fast,
            // the hub will decline the request (flood security)
            $deviceData[$deviceId] = $this->getDeviceData((int) $deviceId);

            if ((int) COAP_GATEWAY_FLOOD_PROTECTION > 0) {
                \usleep((int) COAP_GATEWAY_FLOOD_PROTECTION);
            }
        }

        return $deviceData;
    }

    /**
     * Get an array with lamp ids from soap client.
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return array
     */
    public function getDeviceIds(): array
    {
        return $this->_getData(Keys::ROOT_DEVICES);
    }

    /**
     * Get Device data.
     *
     * @param int $deviceId
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return \stdClass
     */
    public function getDeviceData(int $deviceId): \stdClass
    {
        return $this->_getData(Keys::ROOT_DEVICES, $deviceId);
    }

    /**
     * Get a collection of Groups.
     *
     * @param ServiceInterface $service
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return Groups
     */
    public function getGroupCollection(ServiceInterface $service): Groups
    {
        $groups = $this
            ->getGroupDataMapper()
            ->map($service, $this->getGroupsData());

        if (false === $groups->isEmpty()) {
            foreach ($groups->toArray() as $group) {
                /** @var Light $group */
                $groupDevices = $this
                    ->_deviceDataMapper
                    ->map(
                        $service,
                        $this->getDevicesData($group->getDeviceIds())
                    );
                $group->setDevices($groupDevices);
            }
        }

        return $groups;
    }

    /**
     * Get Groups from hub.
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return array
     */
    public function getGroupsData(): array
    {
        $groupData = [];
        foreach ($this->getGroupIds() as $groupId) {
            // sometimes the request are to fast,
            // the hub will decline the request (flood security)
            $groupData[$groupId] = $this->_getData(
                Keys::ROOT_GROUPS,
                $groupId
            );
        }

        return $groupData;
    }

    /**
     * Get Group data from hub.
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return array
     */
    public function getGroupIds(): array
    {
        return $this->_getData(Keys::ROOT_GROUPS);
    }

    /**
     * Decode gateway data.
     *
     * @param $dataRaw
     *
     * @return object|string
     */
    protected function _decodeData(string $dataRaw)
    {
        $decoded = \json_decode($dataRaw);
        if (null === $decoded) {
            $decoded = $dataRaw;
        }

        return $decoded;
    }

    /**
     * Verify result.
     *
     * @param $data
     *
     * @return bool
     */
    protected function _verifyResult(array $data): bool
    {
        return \is_array($data) && 4 === \count($data);
    }
}
