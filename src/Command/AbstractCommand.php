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

namespace IKEA\Tradfri\Command;

use IKEA\Tradfri\Helper\CommandRunnerInterface;

abstract class AbstractCommand implements \Stringable, CommandInterface
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

    /**
     * @phpstan-return array<string>|bool
     */
    abstract public function run(CommandRunnerInterface $runner): array|bool;

    protected function verifyResult(mixed $data): bool
    {
        return \is_array($data)
            && (4 === \count($data) || '' === ($data[0] ?? null));
    }
}
