<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Helper;

use IKEA\Tradfri\Exception\RuntimeException;

/**
 * Class Runner.
 */
class Runner
{
    /**
     * File descriptors passed to the process.
     */
    public const DESCRIPTORS
        = [
            0 => ['pipe', 'r'],  // stdin
            1 => ['pipe', 'w'],  // stdout
            2 => ['pipe', 'w'],   // stderr
        ];

    /**
     * Execute a command and return it's output. Either wait
     * until the command exits or the timeout has expired.
     * Found at @see https://stackoverflow.com/a/20992213/3578430.
     *
     * @param string $cmd                  command to execute
     * @param int    $timeout              timeout in seconds
     * @param bool   $asArray
     * @param bool   $skipEmptyBufferError
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return array|string
     */
    public function execWithTimeout(
        string $cmd,
        int $timeout,
        bool $asArray = null,
        bool $skipEmptyBufferError = null
    ) {
        // Start the process.
        $process = \proc_open('exec '.$cmd, self::DESCRIPTORS, $pipes);

        if (!\is_resource($process)) {
            throw new RuntimeException('Could not execute process');
        }

        // Set the stdout stream to none-blocking.
        \stream_set_blocking($pipes[1], false);

        // Output buffer.
        $buffer = $this->_startProcess($timeout, $pipes, $process, '');

        // Check if there were any errors.
        $errors = \stream_get_contents($pipes[2]);

        if (!empty($errors) && empty($buffer)) {
            $this->_parseErrors($skipEmptyBufferError, $errors);
        }

        // Kill the process in case the timeout expired and it's still running.
        // If the process already exited this won't do anything.
        $this->_killProcess($process);

        // Close all streams.
        $this->_closeStreams($pipes, $process);

        if (true === $asArray) {
            return \explode("\n", $buffer);
        }

        return $buffer;
    }

    /**
     * Kill process.
     *
     * @param $process
     */
    private function _killProcess($process): void
    {
        if (\proc_terminate($process, 9)) {
            throw new RuntimeException('timeout expired');
        }
    }

    /**
     * Parse errors.
     */
    private function _parseErrors(bool $skipEmptyBufferError, string $errors): void
    {
        $parts = \explode("\n", $errors);
        switch (\count($parts)) {
            case 2 && !empty($parts[1]):
            case 3:
                $errorMessage = $parts[1];

                break;
            default:
                $errorMessage = 'Unknown error';
        }
        if (false === $skipEmptyBufferError) {
            throw new RuntimeException($errorMessage);
        }
    }

    /**
     * Start process.
     *
     * @param $process
     */
    private function _startProcess(
        int $timeout,
        array $pipes,
        $process,
        string $buffer
    ): string {
        // Turn the timeout into microseconds.
        $timeout *= 1000000;
        while ($timeout > 0) {
            $start = \microtime(true);

            // Wait until we have output or the timer expired.
            $read = [$pipes[1]];
            $other = [];
            \stream_select($read, $other, $other, 0, (int) $timeout);

            // Get the status of the process.
            // Do this before we read from the stream,
            // this way we can't lose the last bit
            // of output if the process dies between these functions.
            $status = \proc_get_status($process);

            // Read the contents from the buffer.
            // This function will always return immediately
            // as the stream is none-blocking.
            $buffer .= \stream_get_contents($pipes[1]);

            if (!$status['running']) {
                // Break from this loop if the process exited
                // before the timeout.
                break;
            }

            // Subtract the number of microseconds that we waited.
            $timeout -= (\microtime(true) - $start) * 1000000;
        }

        return $buffer;
    }

    /**
     * Close all streams.
     *
     * @param $process
     */
    protected function _closeStreams(array $pipes, $process): void
    {
        \fclose($pipes[0]);
        \fclose($pipes[1]);
        \fclose($pipes[2]);

        \proc_close($process);
    }
}
