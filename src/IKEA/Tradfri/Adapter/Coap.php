<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Adapter;

use const JSON_THROW_ON_ERROR;
use IKEA\Tradfri\Collection\Devices;
use IKEA\Tradfri\Collection\Groups;
use IKEA\Tradfri\Command\Coap\Keys;
use IKEA\Tradfri\Command\Coaps;
use IKEA\Tradfri\Exception\RuntimeException;
use IKEA\Tradfri\Group\Light;
use IKEA\Tradfri\Helper\Runner;
use IKEA\Tradfri\Mapper\MapperInterface;
use IKEA\Tradfri\Service\ServiceInterface;
use JsonException;
use stdClass;
use function count;
use function is_array;
use function is_object;

class Coap extends AdapterAbstract
{
    public const COULD_NOT_SWITCH_STATE = 'Could not switch state';

    protected Coaps $commands;

    public function __construct(
        Coaps $commands,
        MapperInterface $deviceDataMapper,
        MapperInterface $groupDataMapper
    ) {
        $this->commands = $commands;
        parent::__construct($deviceDataMapper, $groupDataMapper);
    }

    /**
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function getType(int $deviceId): string
    {
        $data = $this->_getData(Keys::ROOT_DEVICES, $deviceId);
        if (is_object($data)
            && property_exists($data, Keys::ATTR_DEVICE_INFO)
            && property_exists($data, Keys::ATTR_DEVICE_INFO_TYPE)
        ) {
            return $data
                ->{Keys::ATTR_DEVICE_INFO}
                ->{Keys::ATTR_DEVICE_INFO_TYPE};
        }

        throw new RuntimeException('invalid coap response');
    }

    /**
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return array|stdClass|string
     */
    protected function _getData(string $requestType, int $deviceId = null)
    {
        $command = $this->commands->getCoapsCommandGet($requestType);

        if (null !== $deviceId) {
            $command .= '/' . $deviceId;
        }

        $dataRaw = $this->commands->parseResult(
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
     * @throws \IKEA\Tradfri\Exception\RuntimeException
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
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function changeLightState(int $deviceId, bool $toState): bool
    {
        // run command
        $data = (new Runner())
            ->execWithTimeout(
                $this->commands->getLightSwitchCommand($deviceId, $toState),
                2,
                true,
                true
            );

        // verify result
        if (is_array($data) && empty($data[0])) {
            /*
             * @example data response is now empty since hub update
             * so we only check if there is no error message inside
             */
            return true;
        }

        throw new RuntimeException(self::COULD_NOT_SWITCH_STATE);
    }

    /**
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function changeGroupState(int $groupId, bool $toState): bool
    {
        // run command
        $data = (new Runner())
            ->execWithTimeout(
                $this->commands->getGroupSwitchCommand($groupId, $toState),
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
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function setLightBrightness(int $lightId, int $level): bool
    {
        // run command
        $data = (new Runner())->execWithTimeout(
            $this->commands->getLightDimmerCommand($lightId, $level),
            2,
            true
        );

        // verify result
        if ($this->_verifyResult($data)) {
            return true;
        }

        throw new RuntimeException(self::COULD_NOT_SWITCH_STATE);
    }

    public function setRollerBlindPosition(int $rollerBlindId, int $level): bool
    {
        // run command
        $data = (new Runner())->execWithTimeout(
            $this->commands->getRollerBlindDarkenedStateCommand($rollerBlindId, $level),
            2,
            true
        );

        // @todo: fix validation
        return true;
    }

    /**
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function setGroupBrightness(int $groupId, int $level): bool
    {
        // run command
        $data = (new Runner())->execWithTimeout(
            $this->commands->getGroupDimmerCommand($groupId, $level),
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
     * @throws \IKEA\Tradfri\Exception\RuntimeException
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
     * @throws \IKEA\Tradfri\Exception\RuntimeException
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

            /* @phpstan-ignore-next-line */
            if ((int) COAP_GATEWAY_FLOOD_PROTECTION >= 100) {
                usleep((int) COAP_GATEWAY_FLOOD_PROTECTION);
            }
        }

        return $deviceData;
    }

    /**
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function getDeviceIds(): array
    {
        return $this->_getData(Keys::ROOT_DEVICES);
    }

    /**
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function getDeviceData(int $deviceId): stdClass
    {
        return $this->_getData(Keys::ROOT_DEVICES, $deviceId);
    }

    /**
     * @throws \IKEA\Tradfri\Exception\RuntimeException
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
     * @throws \IKEA\Tradfri\Exception\RuntimeException
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
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function getGroupIds(): array
    {
        return $this->_getData(Keys::ROOT_GROUPS);
    }

    /**
     * @return object|string
     *
     * @throws JsonException
     */
    protected function _decodeData(string $dataRaw)
    {
        $decoded = json_decode($dataRaw, false, 512, JSON_THROW_ON_ERROR);
        if (null === $decoded) {
            $decoded = $dataRaw;
        }

        return $decoded;
    }

    /**
     * @param string|array $data
     */
    protected function _verifyResult($data): bool
    {
        return is_array($data) && 4 === count($data);
    }
}
