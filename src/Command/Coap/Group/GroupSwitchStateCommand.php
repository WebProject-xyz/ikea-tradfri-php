<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025-2026 Benjamin Fahl
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
use IKEA\Tradfri\Helper\CommandRunnerInterface;

final class GroupSwitchStateCommand extends Put implements \Stringable
{
    public function __construct(
        CoapGatewayAuthConfigDto $authConfig,
        private readonly int $groupId,
        private readonly bool $state,
    ) {
        parent::__construct($authConfig);
    }

    #[\Override()]
    public function __toString(): string
    {
        return $this->requestCommand(
            Request::RootGroups->withTargetId($this->groupId),
            CoapGatewayRequestPayloadDto::fromValues(
                Keys::ATTR_DEVICE_STATE,
                '',
                (int) $this->state,
            )->toGroupFormat(),
        );
    }

    #[\Override()]
    public function run(CommandRunnerInterface $runner): bool
    {
        $result = $runner->execWithTimeout(
            (string) $this,
            2,
            true,
        );

        return $this->verifyResult($result);
    }
}
