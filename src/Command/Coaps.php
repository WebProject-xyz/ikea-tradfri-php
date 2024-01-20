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

namespace IKEA\Tradfri\Command;

use IKEA\Tradfri\Command\Coap\Keys;
use IKEA\Tradfri\Exception\RuntimeException;
use IKEA\Tradfri\Helper\CommandRunner;
use IKEA\Tradfri\Helper\CommandRunnerInterface;
use const FILTER_VALIDATE_IP;

/**
 * @deprecated will be removed in v1.0.0
 */
final class Coaps
{
    final public const PAYLOAD_START    = ' -e \'{ "';
    final public const PAYLOAD_OPEN     = '": [{ "';
    final public const PAYLOAD_END      = ' }] }\' ';
    final public const COAP_COMMAND_PUT = 'coap-client -m put -u "%s" -k "%s"';
    private const COLOR_WARM            = 'warm';
    private const COLOR_NORMAL          = 'normal';
    private const COLOR_COLD            = 'cold';
    private const COLORS                = [
        self::COLOR_COLD,
        self::COLOR_NORMAL,
        self::COLOR_WARM,
    ];
    private string $ip;

    /**
     * @throws \InvalidArgumentException
     * @throws RuntimeException
     */
    public function __construct(
        /**
         * @phpstan-param non-empty-string $gatewayAddress
         */
        string $gatewayAddress,
        /**
         * @phpstan-var non-empty-string
         */
        protected string $secret,
        /**
         * @phpstan-var non-empty-string
         */
        private readonly string $apiKey,
        /**
         * @phpstan-var non-empty-string
         */
        private readonly string $username,
        private readonly CommandRunnerInterface $runner = new CommandRunner(),
    ) {
        $this->setIp($gatewayAddress);
    }

    /**
     * @throws RuntimeException
     */
    public function getSharedKeyFromGateway(): string
    {
        // get command to switch light
        $onCommand = $this->getPreSharedKeyCommand();

        // run command
        $result = $this->parseResult(
            $this->runner->execWithTimeout($onCommand, 2),
        );

        // verify result
        if (\is_string($result)) {
            return $result;
        }

        throw new RuntimeException('Could not get api key');
    }

    public function getPreSharedKeyCommand(): string
    {
        return \sprintf(
            Post::COAP_COMMAND,
            'Client_identity',
            $this->secret,
        )
        . ' -e \'{"9090":"' . $this->username . '"}\''
        . $this->_getRequestTypeCoapsUrl(
            Keys::ROOT_GATEWAY . '/' . Keys::ATTR_AUTH,
        );
    }

    public function parseResult(array $result): false|string
    {
        $parsed = false;
        foreach ($result as $part) {
            if (!empty($part)
                &&   !\str_contains((string) $part, 'decrypt')
                &&   !\str_contains((string) $part, 'v:1')) {
                $parsed = (string) $part;

                break;
            }
        }

        return $parsed;
    }

    public function getCoapsCommandGet(int|string $requestType): string
    {
        return \sprintf(
            Get::COAP_COMMAND,
            $this->username,
            $this->apiKey,
        ) . $this->_getRequestTypeCoapsUrl($requestType);
    }

    public function getCoapsCommandPost(int|string $requestType, string $inject): string
    {
        return \sprintf(
            Post::COAP_COMMAND,
            $this->username,
            $this->apiKey,
        )
        . $inject . $this->_getRequestTypeCoapsUrl($requestType);
    }

    public function getLightSwitchCommand(int $deviceId, bool $state): string
    {
        return $this->getCoapsCommandPut(
            Keys::ROOT_DEVICES . '/' . $deviceId,
            self::PAYLOAD_START
            . Keys::ATTR_LIGHT_CONTROL
            . self::PAYLOAD_OPEN
            . Keys::ATTR_DEVICE_STATE . '": ' . ($state ? '1' : '0')
            . self::PAYLOAD_END,
        );
    }

    public function getCoapsCommandPut(int|string $requestType, string $inject): string
    {
        return \sprintf(
            Put::COAP_COMMAND,
            $this->username,
            $this->apiKey,
        )
        . $inject
        . $this->_getRequestTypeCoapsUrl($requestType);
    }

    public function getGroupSwitchCommand(int $groupId, bool $state): string
    {
        return $this->getCoapsCommandPut(
            Keys::ROOT_GROUPS . '/' . $groupId,
            self::PAYLOAD_START . Keys::ATTR_DEVICE_STATE . '": ' . ($state ? '1'
                : '0') . ' }\' ',
        );
    }

    public function getGroupDimmerCommand(int $groupId, int $value): string
    {
        return $this->getCoapsCommandPut(
            Keys::ROOT_GROUPS . '/' . $groupId,
            self::PAYLOAD_START . Keys::ATTR_LIGHT_DIMMER . '": ' . (int) \round(
                $value * 2.55,
            ) . ' }\' ',
        );
    }

    public function getLightDimmerCommand(int $groupId, int $value): string
    {
        return $this->getCoapsCommandPut(
            Keys::ROOT_DEVICES . '/' . $groupId,
            self::PAYLOAD_START
            . Keys::ATTR_LIGHT_CONTROL
            . self::PAYLOAD_OPEN
            . Keys::ATTR_LIGHT_DIMMER . '": ' . (int) \round($value * 2.55)
            . self::PAYLOAD_END,
        );
    }

    public function getRollerBlindDarkenedStateCommand(int $deviceId, int $value): string
    {
        return $this->getCoapsCommandPut(
            Keys::ROOT_DEVICES . '/' . $deviceId,
            self::PAYLOAD_START
            . Keys::ATTR_START_BLINDS
            . self::PAYLOAD_OPEN
            . Keys::ATTR_BLIND_CURRENT_POSITION . '": ' . (float) $value
            . self::PAYLOAD_END,
        );
    }

    /**
     * @phpstan-param value-of<\IKEA\Tradfri\Command\Coaps::COLORS>|string $color
     *
     * @throws RuntimeException
     */
    public function getLightColorCommand(int $groupId, string $color): string
    {
        $payload = self::PAYLOAD_START
            . Keys::ATTR_LIGHT_CONTROL
            . self::PAYLOAD_OPEN
            . Keys::ATTR_LIGHT_COLOR_X
            . '": %s, "'
            . Keys::ATTR_LIGHT_COLOR_Y
            . '": %s }] }\' ';

        if (!\in_array($color, self::COLORS, true)) {
            throw new RuntimeException('unknown color');
        }

        $payload = match ($color) {
            self::COLOR_WARM   => \sprintf($payload, '33135', '27211'),
            self::COLOR_NORMAL => \sprintf($payload, '30140', '26909'),
            self::COLOR_COLD   => \sprintf($payload, '24930', '24684'),
        };

        return $this->getCoapsCommandPut(
            Keys::ROOT_DEVICES . '/' . $groupId,
            $payload,
        );
    }

    /**
     * @throws \InvalidArgumentException
     */
    private function setIp(string $gatewayAddress): void
    {
        if (\filter_var($gatewayAddress, FILTER_VALIDATE_IP)) {
            $this->ip = $gatewayAddress;

            return;
        }

        throw new \InvalidArgumentException('Invalid ip');
    }

    private function _getRequestTypeCoapsUrl(int|string $requestType): string
    {
        return ' "coaps://' . $this->ip . ':5684/' . $requestType . '"';
    }
}
