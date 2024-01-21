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

use IKEA\Tradfri\Helper\CommandRunnerInterface;
use IKEA\Tradfri\Values\CoapCommandPattern;

/**
 * @final
 */
class Put extends AbstractCommand
{
    final public const COAP_COMMAND   = 'coap-client -m put -u "%s" -k "%s"';
    private const COAP_COMMAND_FORMAT = '%s %s "%s/%s"';

    public function __construct(\IKEA\Tradfri\Dto\CoapGatewayAuthConfigDto $authConfig)
    {
        parent::__construct($authConfig, CoapCommandPattern::PUT);
    }

    public function requestCommand(Request|string $request, string $payload): string
    {
        if ($request instanceof Request) {
            $request = $request->value;
        }

        return \sprintf(self::COAP_COMMAND_FORMAT, $this->command(), $payload, $this->authConfig->getGatewayUrl(), $request);
    }

    public function run(CommandRunnerInterface $runner): bool
    {
        $result= $runner->execWithTimeout(
            (string) $this,
            2,
            true,
        );

        return $this->verifyResult($result);
    }
}
