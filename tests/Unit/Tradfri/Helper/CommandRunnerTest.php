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

namespace IKEA\Tests\Unit\Tradfri\Helper;

final class CommandRunnerTest extends \Codeception\Test\Unit
{
    public function testExecWithTimeout(): void
    {
        // Arrange
        $runner = new \IKEA\Tradfri\Helper\CommandRunner();

        // Act
        $result = $runner->execWithTimeout(cmd: 'ls tests/Unit', timeout: 1);

        // Assert
        $this->assertStringContainsStringIgnoringLineEndings('bootstrap.php', $result);
        $this->assertStringContainsStringIgnoringLineEndings('Tradfri', $result);
    }

    public function testExecWithTimeoutAsArray(): void
    {
        // Arrange
        $runner = new \IKEA\Tradfri\Helper\CommandRunner();

        // Act
        $result = $runner->execWithTimeout(cmd: 'ls tests/Unit', timeout: 1, asArray: true);

        // Assert
        $this->assertContains('bootstrap.php', $result);
        $this->assertContains('Tradfri', $result);
    }

    public function testExecWithTimeoutGetError(): void
    {
        // Arrange
        $runner = new \IKEA\Tradfri\Helper\CommandRunner();

        // Act
        $result = $runner->execWithTimeout(cmd: 'asdjalgualg', timeout: 1, asArray: false, throw: false);

        // Assert
        $this->assertStringContainsString('not found', $result);
    }

    public function testExecWithTimeoutGetErrorThrow(): void
    {
        // Arrange
        $runner = new \IKEA\Tradfri\Helper\CommandRunner();

        // Act
        $this->expectExceptionMessageMatches('#not found#');
        $runner->execWithTimeout(cmd: 'asdjalgualg', timeout: 1, asArray: false, throw: true);
    }
}
