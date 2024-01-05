<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Helper;

use IKEA\Tradfri\Exception\RuntimeException;
use Symfony\Component\Process\Process;
use function count;

class CommandRunner
{
    /**
     * Execute a command and return it's output. Either wait
     * until the command exits or the timeout has expired.
     * Found at @see https://stackoverflow.com/a/20992213/3578430.
     *
     * @param string $cmd     command to execute
     * @param int    $timeout timeout in seconds
     */
    public function execWithTimeout(
        string $cmd,
        int $timeout,
        bool $asArray = null,
        bool $throw = false
    ): array|string {
        $process = Process::fromShellCommandline(command: $cmd, timeout: $timeout);
        $process->run();

        if (!$process->isSuccessful()) {
            return $this->_parseErrors($throw, $process->getErrorOutput());
        }

        $output = $process->getOutput();

        return $asArray
            ? explode("\n", trim($output))
            : $output;
    }

    private function _parseErrors(bool $throw, string $errors): string
    {
        $parts        = explode("\n", $errors);

        $errorMessage = match (true) {
            2 === count($parts) && !empty($parts[1]), 3 === count($parts) => $parts[1],
            default => 'Unknown error',
        };

        return $throw ? throw new RuntimeException($errorMessage) : $errorMessage;
    }
}
