<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Adapter;

use IKEA\Tradfri\Collection\Devices;
use IKEA\Tradfri\Collection\Groups;
use IKEA\Tradfri\Command\Coaps;
use IKEA\Tradfri\Device\Group;
use IKEA\Tradfri\Exception\RuntimeException;
use IKEA\Tradfri\Group\Light;
use IKEA\Tradfri\Helper\CoapCommandKeys;
use IKEA\Tradfri\Helper\Online;
use IKEA\Tradfri\Helper\Runner;
use IKEA\Tradfri\Mapper\MapperInterface;
use IKEA\Tradfri\Service\ServiceInterface;

/**
 * Class Coap.
 */
class Coap extends AdapterAbstract
{
    /**
     * @var Coaps
     */
    private $commands;

    /**
     * @var bool
     */
    private $isOnline = false;

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
        $this->commands = $commands;

        $this->checkOnline($commands->getIp());

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
        $data = $this->_getData(CoapCommandKeys::KEY_GET_DATA, $deviceId);

        if (isset($data->{CoapCommandKeys::KEY_DATA}->{CoapCommandKeys::KEY_TYPE})) {
            return $data->{CoapCommandKeys::KEY_DATA}->{CoapCommandKeys::KEY_TYPE};
        }

        throw new RuntimeException('invalid coap response');
    }

    /**
     * Get data from coaps client.
     *
     * @param string   $requestType
     * @param int|null $deviceId
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return array|\stdClass|string
     */
    protected function _getData(string $requestType, int $deviceId = null)
    {
        if ($this->isOnline()) {
            $command = $this->commands->getCoapsCommandGet($requestType);

            if ($deviceId !== null) {
                $command .= '/'.$deviceId;
            }

            $dataRaw = $this->commands->parseResult(
                Runner::execWithTimeout($command, 1)
            );

            if ($dataRaw !== false) {
                $decoded = \json_decode($dataRaw);
                if (null === $decoded) {
                    return $dataRaw;
                }

                return $decoded;
            }

            throw new RuntimeException('invalid hub response');
        }

        throw new RuntimeException('api is offline');
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
        return $this->_getData(CoapCommandKeys::KEY_GET_DATA, $deviceId);
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
        $data = $this->_getData(CoapCommandKeys::KEY_GET_DATA, $deviceId);

        if (isset($data->{CoapCommandKeys::KEY_DATA}->{'0'})) {
            return $data->{CoapCommandKeys::KEY_DATA}->{'0'};
        }

        throw new RuntimeException('invalid hub response');
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
        return $this->_getData(CoapCommandKeys::KEY_GET_DATA);
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
        return $this->_getData(CoapCommandKeys::KEY_GET_GROUPS);
    }

    /**
     * Get Groups from hub.
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return array
     */
    public function getGroupsData():array
    {
        $groupData = [];
        foreach ($this->getGroupIds() as $groupId) {
            // sometimes the request are to fast,
            // the hub will decline the request (flood security)
            $groupData[$groupId]
                = $this->_getData(
                    CoapCommandKeys::KEY_GET_GROUPS,
                    $groupId
                );
            //\sleep(1);
        }

        return $groupData;
    }

    /**
     * Get devices.
     *
     * @param array|null $deviceIds
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return array
     */
    public function getDevicesData(array $deviceIds = null): array
    {
        if ($deviceIds === null) {
            $deviceIds = $this->getDeviceIds();
        }
        $deviceData = [];
        foreach ($deviceIds as $deviceId) {
            // sometimes the request are to fast,
            // the hub will decline the request (flood security)
            $deviceData[$deviceId] = $this->getDeviceData((int) $deviceId);
            //\sleep(1);
        }

        return $deviceData;
    }

    /**
     * Get isOnline.
     *
     * @return bool
     */
    public function isOnline(): bool
    {
        return $this->isOnline;
    }

    /**
     * Set IsOnline.
     *
     * @param bool $isOnline
     *
     * @return Coap
     */
    public function setOnline(bool $isOnline): self
    {
        $this->isOnline = $isOnline;

        return $this;
    }

    /**
     * Check online state.
     *
     * @param string $ipAddress
     *
     * @return bool
     */
    public function checkOnline(string $ipAddress): bool
    {
        $state = Online::isOnline($ipAddress);
        $this->setOnline($state);

        return $state;
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
        // get command to switch light
        $onCommand = $this
            ->commands
            ->getLightSwitchCommand($deviceId, $toState);

        // run command
        $data = Runner::execWithTimeout(
            $onCommand,
            2,
            true,
            true
        );
        // verify result
        if (\is_array($data) && empty($data[0])) {
            /**
             * @example data response is now empty since hub update
             * so we only check if there is no error message inside
             */
            return true;
        }

        throw new RuntimeException('Could not switch state');
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
        // get command to switch light
        $onCommand = $this->commands->getGroupSwitchCommand($groupId, $toState);

        // run command
        $data = Runner::execWithTimeout($onCommand, 2);

        // verify result
        if (\is_array($data) && \count($data) === 4) {
            return true;
        }

        throw new RuntimeException('Could not switch state');
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
        // get command to dim light
        $onCommand = $this->commands->getLightDimmerCommand($lightId, $level);

        // run command
        $data = Runner::execWithTimeout($onCommand, 2);

        // verify result
        if (\is_array($data) && \count($data) === 4) {
            return true;
        }

        throw new RuntimeException('Could not switch state');
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
        // get command to switch light
        $onCommand = $this->commands->getGroupDimmerCommand($groupId, $level);

        // run command
        $data = Runner::execWithTimeout($onCommand, 2);

        // verify result
        if (\is_array($data) && \count($data) === 4) {
            return true;
        }

        throw new RuntimeException('Could not switch state');
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
        return $this->deviceDataMapper->map($service, $this->getDevicesData());
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
        $groups = $this->groupDataMapper->map($service, $this->getGroupsData());

        if ($groups->isEmpty() === false) {
            foreach ($groups->toArray() as $group) {
                /** @var Light $group */
                $groupDevices = $this->deviceDataMapper->map($service, $this->getDevicesData($group->getDeviceIds()));
                $group->setDevices($groupDevices);
            }
        }

        return $groups;
    }
}
