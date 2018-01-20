<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Device;

use IKEA\Tradfri\Exception\TypeException;
use IKEA\Tradfri\Service\Api;
use IKEA\Tradfri\Service\ServiceInterface;
use JsonSerializable;

/**
 * Class Device.
 */
abstract class Device implements JsonSerializable
{
    const TYPE_MOTION_SENSOR  = 'TRADFRI motion sensor';
    const TYPE_REMOTE_CONTROL = 'TRADFRI remote control';
    const TYPE_DIMMER         = 'TRADFRI dimmer';
    const TYPE_BLUB_E27_WS    = 'TRADFRI bulb E27 WS opal 980lm';
    const TYPE_BLUB_E27_W     = 'TRADFRI bulb E27 W opal 1000lm';
    const TYPE_BLUB_GU10      = 'TRADFRI bulb GU10 WS 400lm';

    /**
     * @var array
     */
    protected static $_lightblubTypes
        = [
            self::TYPE_BLUB_GU10,
            self::TYPE_BLUB_E27_W,
            self::TYPE_BLUB_E27_WS,
        ];

    /**
     * @var int
     */
    protected $_id;

    /**
     * @var string
     */
    protected $_name;

    /**
     * @var string
     */
    protected $_manufacturer;

    /**
     * @var string
     */
    protected $_type;

    /**
     * @var string
     */
    protected $_version;

    /**
     * @var Api|ServiceInterface
     */
    protected $_service;

    /**
     * Lightbulb constructor.
     *
     * @param int    $deviceId
     * @param string $type
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function __construct(int $deviceId, string $type)
    {
        $this->setType($type);
        $this->_id = $deviceId;
    }

    /**
     * Get Name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->_name;
    }

    /**
     * Set Name.
     *
     * @param mixed $name
     *
     * @return Device
     */
    public function setName($name): self
    {
        $this->_name = $name;

        return $this;
    }

    /**
     * Get Manufacturer.
     *
     * @return string
     */
    public function getManufacturer(): string
    {
        return $this->_manufacturer;
    }

    /**
     * Set Manufacturer.
     *
     * @param string $manufacturer
     *
     * @return Device
     */
    public function setManufacturer($manufacturer): self
    {
        $this->_manufacturer = $manufacturer;

        return $this;
    }

    /**
     * Get Id.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->_id;
    }

    /**
     * Set Id.
     *
     * @param int $deviceId
     *
     * @return Device
     */
    public function setId(int $deviceId): self
    {
        $this->_id = $deviceId;

        return $this;
    }

    /**
     * Get Version.
     *
     * @return string
     */
    public function getVersion(): string
    {
        return $this->_version;
    }

    /**
     * Set Version.
     *
     * @param string $version
     *
     * @return Device
     */
    public function setVersion(string $version): self
    {
        $this->_version = $version;

        return $this;
    }

    /**
     * Is this a lightblub.
     *
     * @return bool
     */
    public function isLightbulb(): bool
    {
        return \in_array($this->getType(), self::$_lightblubTypes, true);
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->_type;
    }

    /**
     * Set Type.
     *
     * @param string $type
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return Device
     */
    public function setType($type): self
    {
        if ($this->isValidType($type)) {
            $this->_type = $type;
        }

        return $this;
    }

    /**
     * Get Service.
     *
     * @return ServiceInterface|Api
     */
    public function getService(): ServiceInterface
    {
        return $this->_service;
    }

    /**
     * Set Service.
     *
     * @param ServiceInterface $service
     *
     * @return Device
     */
    public function setService(ServiceInterface $service): self
    {
        $this->_service = $service;

        return $this;
    }

    /**
     * Validate Type.
     *
     * @param $type
     *
     * @throws \IKEA\Tradfri\Exception\TypeException
     *
     * @return bool
     */
    public function isValidType($type): bool
    {
        switch ($type) {
            case self::TYPE_BLUB_E27_W:
            case self::TYPE_BLUB_E27_WS:
            case self::TYPE_BLUB_GU10:
            case self::TYPE_MOTION_SENSOR:
            case self::TYPE_REMOTE_CONTROL:
            case self::TYPE_DIMMER:
                // todo add more types
                break;
            default:
                throw new TypeException('unknown type');
        }

        return true;
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     *
     * @return mixed data which can be serialized by <b>json_encode</b>,
     *               which is a value of any type other than a resource.
     *
     * @since 5.4.0
     */
    public function jsonSerialize(): array
    {
        $data = [];

        foreach (\get_class_methods(static::class) as $method) {
            if ($method !== 'getService' && \strpos($method, 'get') === 0) {
                $key = \strtolower((string) \substr($method, 3));
                $data[$key]
                    = $this->$method();
            }
        }

        return $data;
    }
}
