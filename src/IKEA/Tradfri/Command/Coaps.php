<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Command;

use IKEA\Tradfri\Exception\RuntimeException;
use IKEA\Tradfri\Helper\CoapCommandKeys;
use IKEA\Tradfri\Helper\Runner;

/**
 * Class Coaps.
 */
class Coaps
{
    const COAP_COMMAND_GET = 'coap-client -m get -u "%s" -k "%s"';
    const COAP_COMMAND_PUT = 'coap-client -m put -u "%s" -k "%s"';
    const COAP_COMMAND_POST = 'coap-client -m post -u "%s" -k "%s"';

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $ip;

    /**
     * @var string
     */
    private $secret;

    /**
     * Coaps constructor.
     *
     * @param string $ip
     * @param string $secret
     * @param string $username
     *
     * @throws \InvalidArgumentException
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     */
    public function __construct(string $ip, string $secret, $username = 'wrapper')
    {
        $this->setIp($ip);
        $this->secret = $secret;
        if (!\defined('COAP_API_KEY')) {
            throw new RuntimeException('COAP_API_KEY not set');
        }
        $this->setApiKey(COAP_API_KEY);
        $this->setUsername($username);
    }

    /**
     * Get SharedKey from gateway.
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return string
     */
    public function getSharedKeyFromGateway(): string
    {
        // get command to switch light
        $onCommand = $this->getPreSharedKeyCommand();

        // run command
        $result = $this->parseResult(Runner::execWithTimeout($onCommand, 2));

        // verify result
        if (isset($result->{CoapCommandKeys::KEY_SHARED_KEY})) {
            return $result->{CoapCommandKeys::KEY_SHARED_KEY};
        }

        throw new RuntimeException('Could not get api key');
    }

    /**
     * Parse result.
     *
     * @param array $result
     *
     * @return false|string
     */
    public function parseResult(array $result)
    {
        if (\count($result) === 2 && !empty($result[0])) {
            return $result[0];
        }

        return false;
    }

    /**
     * Set api username.
     *
     * @param string $username
     *
     * @return $this
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get Username.
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Set ApiKey.
     *
     * @param string $apiKey
     *
     * @return Coaps
     */
    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Get ApiKey.
     *
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * Set and filter ip.
     *
     * @param $ip
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function setIp(string $ip): self
    {
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            $this->ip = $ip;

            return $this;
        }

        throw new \InvalidArgumentException('Invalid ip');
    }

    /**
     * Get Ip.
     *
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * Get CoapsCommand GET string.
     *
     * @param string|int $requestType
     *
     * @return string
     */
    public function getCoapsCommandGet($requestType): string
    {
        return \sprintf(self::COAP_COMMAND_GET, $this->getUsername(), $this->getApiKey()).' "coaps://'.$this->ip.':5684/'.$requestType.'"';
    }

    /**
     * Get CoapsCommand PUT string.
     *
     * @param string|int $requestType
     * @param string     $inject
     *
     * @return string
     */
    public function getCoapsCommandPut($requestType, string $inject): string
    {
        return \sprintf(self::COAP_COMMAND_PUT, $this->getUsername(), $this->getApiKey()).$inject.' "coaps://'.$this->ip.':5684/'.$requestType.'"';
    }

    /**
     * Get CoapsCommand POST string.
     *
     * @param string|int $requestType
     * @param string     $inject
     *
     * @return string
     */
    public function getCoapsCommandPost($requestType, string $inject): string
    {
        return \sprintf(self::COAP_COMMAND_POST, $this->getUsername(), $this->getApiKey()).$inject.' "coaps://'.$this->ip.':5684/'.$requestType.'"';
    }

    /**
     * Get Command to get an api key from gateway.
     *
     * @return string
     */
    public function getPreSharedKeyCommand(): string
    {
        $command = \sprintf(self::COAP_COMMAND_POST, 'Client_identity', $this->secret)
            .' -e \'{"9090":"'.$this->getUsername().'"}\''
            .' "coaps://'.$this->ip.':5684/'.CoapCommandKeys::KEY_GET_SHARED_KEY.'"';

        return $command;
    }

    /**
     * Get Command to switch light on or off.
     *
     * @param int  $deviceId
     * @param bool $state
     *
     * @return string
     */
    public function getLightSwitchCommand(int $deviceId, bool $state): string
    {
        return $this->getCoapsCommandPut(
            CoapCommandKeys::KEY_GET_DATA.'/'.$deviceId,
            ' -e \'{ "'.CoapCommandKeys::KEY_DEVICE_DATA.'": [{ "'.CoapCommandKeys::KEY_ONOFF.'": '.($state ? '1' : '0').' }] }\' '
        );
    }

    /**
     * Get command to switch group on / off.
     *
     * @param int  $groupId
     * @param bool $state
     *
     * @return string
     */
    public function getGroupSwitchCommand(int $groupId, bool $state): string
    {
        return $this->getCoapsCommandPut(
            CoapCommandKeys::KEY_GET_GROUPS.'/'.$groupId,
            ' -e \'{ "'.CoapCommandKeys::KEY_ONOFF.'": '.($state ? '1' : '0').' }\' '
        );
    }

    /**
     * Get Command to dim group.
     *
     * @param int $groupId
     * @param int $value
     *
     * @return string
     */
    public function getGroupDimmerCommand(int $groupId, int $value): string
    {
        return $this->getCoapsCommandPut(
            CoapCommandKeys::KEY_GET_GROUPS.'/'.$groupId,
            ' -e \'{ "'.CoapCommandKeys::KEY_DIMMER.'": '.(int) round($value * 2.55).' }\' '
        );
    }

    /**
     * Get Command to dim light.
     *
     * @param int $groupId
     * @param int $value
     *
     * @return string
     */
    public function getLightDimmerCommand(int $groupId, int $value): string
    {
        return $this->getCoapsCommandPut(
            CoapCommandKeys::KEY_GET_DATA.'/'.$groupId,
            ' -e \'{ "'.CoapCommandKeys::KEY_DEVICE_DATA.'": [{ "'.CoapCommandKeys::KEY_DIMMER.'": '.(int) round($value * 2.55).' }] }\' '
        );
    }

    /**
     * Get Command to dim light.
     *
     * @param int    $groupId
     * @param string $color   (warm|normal|cold)
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return string
     */
    public function getLightColorCommand(int $groupId, string $color): string
    {
        $payload = ' -e \'{ "'.CoapCommandKeys::KEY_DEVICE_DATA.'": [{ "'.CoapCommandKeys::KEY_COLOR.'": %s, "'.CoapCommandKeys::KEY_COLOR_2.'": %s }] }\' ';
        switch ($color) {
            case 'warm':
                $payload = \sprintf($payload, '33135', '27211');
                break;
            case 'normal':
                $payload = \sprintf($payload, '30140', '26909');
                break;
            case 'cold':
                $payload = \sprintf($payload, '24930', '24684');
                break;
            default:
                throw new RuntimeException('unknown color');
        }

        return $this->getCoapsCommandPut(
            CoapCommandKeys::KEY_GET_DATA.'/'.$groupId,
            $payload
        );
    }
}
