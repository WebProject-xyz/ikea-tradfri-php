<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Benjamin Fahl.
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/WebProject-xyz/ikea-tradfri-php
 */

namespace IKEA\Tradfri\Dto;

final readonly class CoapGatewayAuthConfigDto
{
    public function __construct(
        private string $username,
        #[\SensitiveParameter()]
        private string $apiKey,
        #[\SensitiveParameter()]
        private string $gatewaySecret,
        private string $gatewayIp,
    ) {
        self::checkIp($this->gatewayIp);
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function getGatewaySecret(): string
    {
        return $this->gatewaySecret;
    }

    public function getGatewayUrl(): string
    {
        return \sprintf('coaps://%s:5684', $this->gatewayIp);
    }

    public function injectToCommand(\IKEA\Tradfri\Values\CoapCommandPattern $commandPattern): string
    {
        return \sprintf(
            $commandPattern->value,
            $this->getUsername(),
            $this->getApiKey(),
        );
    }

    /**
     * @throws \InvalidArgumentException
     */
    private static function checkIp(string $gatewayAddress): void
    {
        if (\filter_var($gatewayAddress, \FILTER_VALIDATE_IP)) {
            return;
        }

        throw new \InvalidArgumentException('Invalid ip');
    }
}
