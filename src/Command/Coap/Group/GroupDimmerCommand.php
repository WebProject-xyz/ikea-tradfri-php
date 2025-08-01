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

namespace IKEA\Tradfri\Command\Coap\Group;

use IKEA\Tradfri\Command\Coap\Keys;
use IKEA\Tradfri\Command\Put;
use IKEA\Tradfri\Command\Request;
use IKEA\Tradfri\Dto\CoapGatewayAuthConfigDto;
use IKEA\Tradfri\Dto\CoapGatewayRequestPayloadDto;

final class GroupDimmerCommand extends Put
{
    public function __construct(
        CoapGatewayAuthConfigDto $authConfig,
        private readonly int $groupId,
        private readonly int $level,
    ) {
        parent::__construct($authConfig);
    }

    #[\Override()]
    public function __toString(): string
    {
        return $this->requestCommand(
            Request::RootGroups->withTargetId($this->groupId),
            CoapGatewayRequestPayloadDto::fromValues(
                Keys::ATTR_LIGHT_DIMMER,
                '',
                (int) \round($this->level * 2.55),
            )->toGroupFormat(),
        );
    }
}
