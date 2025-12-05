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

namespace IKEA\Tradfri\Helper;

use IKEA\Tradfri\Exception\RuntimeException;
use Symfony\Component\Process\Process;

/**
 * @final
 */
class CommandRunner implements CommandRunnerInterface
{
    /**
     * @phpstan-return ($asArray is true ? list<string> : string)
     */
    public function execWithTimeout(
        string $cmd,
        int $timeout,
        ?bool $asArray = null,
        bool $throw = false,
    ): array|string {
        $process = Process::fromShellCommandline(command: $cmd, env: ['LANG=en_EN'], timeout: $timeout);
        $process->run();

        if (!$process->isSuccessful()) {
            return self::_parseErrors($throw, $process->getErrorOutput());
        }

        $output = $process->getOutput();

        return $asArray
            ? \explode("\n", \mb_trim($output))
            : $output;
    }

    private static function _parseErrors(bool $throw, string $errors): string
    {
        $parts        = \explode("\n", $errors);
        $countOfLines = \count($parts);
        $line2IsEmpty = empty($parts[1]);

        $errorMessage = match (true) {
            2 === $countOfLines && !$line2IsEmpty,
            3 === $countOfLines => $parts[1],
            2 === $countOfLines && $line2IsEmpty,
            2 === $countOfLines => $parts[0],
            default             => 'Unknown error',
        };

        return $throw
            ? throw new RuntimeException($errorMessage)
            : $errorMessage;
    }
}
