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

use IKEA\Tradfri\Adapter\Coap as Adapter;
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
        $deviceMapper = new \IKEA\Tradfri\Mapper\DeviceData();
        $groupMapper  = new \IKEA\Tradfri\Mapper\GroupData();
        $commands     = new GatewayHelperCommands(
            $this->authConfig,
        );
        $adapter = new Adapter(authConfig: $this->authConfig, commands: $commands, deviceDataMapper: $deviceMapper, groupDataMapper: $groupMapper);
        $client  = new \IKEA\Tradfri\Client\Client($adapter);

        return new \IKEA\Tradfri\Service\GatewayApiService($client);
    }
}
