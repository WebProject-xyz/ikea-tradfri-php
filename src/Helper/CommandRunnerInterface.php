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

namespace IKEA\Tradfri\Helper;

interface CommandRunnerInterface
{
    /**
     * Execute a command and return it's output. Either wait
     * until the command exits or the timeout has expired.
     * Found at @see https://stackoverflow.com/a/20992213/3578430.
     *
     * @param string $cmd     command to execute
     * @param int    $timeout timeout in seconds
     *
     * @return list<string>|string
     *
     * @phpstan-return ($asArray is true ? list<string> : string)
     */
    public function execWithTimeout(string $cmd, int $timeout, ?bool $asArray = null, bool $throw = false): array|string;
}
