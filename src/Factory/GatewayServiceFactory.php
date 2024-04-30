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

namespace IKEA\Tradfri\Factory;

use IKEA\Tradfri\Adapter\CoapAdapter as Adapter;
use IKEA\Tradfri\Command\GatewayHelperCommands;
use IKEA\Tradfri\Dto\CoapGatewayAuthConfigDto;
use IKEA\Tradfri\Service\ServiceInterface;

final readonly class GatewayServiceFactory
{
    public function __construct(
        private CoapGatewayAuthConfigDto $authConfig,
    ) {
    }

    public function __invoke(): ServiceInterface
    {
        return new \IKEA\Tradfri\Service\GatewayApiService(
            client: new \IKEA\Tradfri\Client\Client(
                adapter: new Adapter(
                    authConfig: $this->authConfig,
                    commands: new GatewayHelperCommands(
                        authConfig: $this->authConfig,
                    ),
                    deviceDataMapper: new \IKEA\Tradfri\Mapper\DeviceData(),
                    groupDataMapper: new \IKEA\Tradfri\Mapper\GroupData(),
                ),
            ),
        );
    }
}
