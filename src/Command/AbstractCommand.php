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

abstract class AbstractCommand implements CommandInterface
{
    public function __construct(
        protected readonly \IKEA\Tradfri\Dto\CoapGatewayAuthConfigDto $authConfig,
        protected \IKEA\Tradfri\Values\CoapCommandPattern $commandPattern,
    ) {
    }

    public function __toString(): string
    {
        return $this->command();
    }

    final public function command(): string
    {
        return $this->authConfig->injectToCommand($this->commandPattern);
    }
}
