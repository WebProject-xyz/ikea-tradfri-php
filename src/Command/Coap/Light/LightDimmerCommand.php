<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Benjamin Fahl
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/WebProject-xyz/ikea-tradfri-php
 */

namespace IKEA\Tradfri\Command\Coap\Light;

use IKEA\Tradfri\Command\Coap\Keys;
use IKEA\Tradfri\Command\Put;
use IKEA\Tradfri\Command\Request;
use IKEA\Tradfri\Dto\CoapGatewayAuthConfigDto;
use IKEA\Tradfri\Dto\CoapGatewayRequestPayloadDto;

final class LightDimmerCommand extends Put
{
    public function __construct(
        CoapGatewayAuthConfigDto $authConfig,
        private readonly int $deviceId,
        private readonly int $value,
    ) {
        parent::__construct($authConfig);
    }

    #[\Override()]
    public function __toString(): string
    {
        return $this->requestCommand(
            Request::RootDevices->withTargetId($this->deviceId),
            (string) CoapGatewayRequestPayloadDto::fromValues(
                Keys::ATTR_LIGHT_CONTROL,
                Keys::ATTR_LIGHT_DIMMER,
                (int) \round($this->value * 2.55),
            ),
        );
    }
}
