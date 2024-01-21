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

use IKEA\Tradfri\Values\CoapCommandPattern;

final class Get extends AbstractCommand
{
    final public const COAP_COMMAND                  = 'coap-client -m get -u "%s" -k "%s"';
    private const COAP_COMMAND_FORMAT                = '%s "%s/%s"';
    private const COAP_COMMAND_FORMAT_WITH_DEVICE_ID = '%s "%s/%s/%s"';

    public function __construct(\IKEA\Tradfri\Dto\CoapGatewayAuthConfigDto $authConfig)
    {
        parent::__construct($authConfig, CoapCommandPattern::GET);
    }

    public function requestCommand(Request|string $request, ?int $deviceId = null): string
    {
        if ($request instanceof Request) {
            $request = $request->value;
        }

        if (null !== $deviceId) {
            return \sprintf(self::COAP_COMMAND_FORMAT_WITH_DEVICE_ID, $this->command(), $this->authConfig->getGatewayUrl(), $request, $deviceId);
        }

        return \sprintf(self::COAP_COMMAND_FORMAT, $this->command(), $this->authConfig->getGatewayUrl(), $request);
    }
}
