<?php

declare(strict_types=1);

namespace IKEA\Tests\Unit\Tradfri\Helper;

class CommandRunnerTest extends \Codeception\Test\Unit
{
    public function testExecWithTimeout(): void
    {
        // Arrange
        $runner = new \IKEA\Tradfri\Helper\CommandRunner();

        // Act
        $result = $runner->execWithTimeout(cmd: 'ls tests/Unit', timeout: 1);

        // Assert
        $this->assertSame(expected: "bootstrap.php\nTradfri\n", actual: $result);
    }

    public function testExecWithTimeoutAsArray(): void
    {
        // Arrange
        $runner = new \IKEA\Tradfri\Helper\CommandRunner();

        // Act
        $result = $runner->execWithTimeout(cmd: 'ls tests/Unit', timeout: 1, asArray: true);

        // Assert
        $this->assertSame(expected: ['bootstrap.php', 'Tradfri'], actual: $result);
    }

    public function testExecWithTimeoutGetError(): void
    {
        // Arrange
        $runner = new \IKEA\Tradfri\Helper\CommandRunner();

        // Act
        $result = $runner->execWithTimeout(cmd: 'asdjalgualg', timeout: 1, asArray: false, throw: false);

        // Assert
        $this->assertSame(expected: 'Unknown error', actual: $result);
    }

    public function testExecWithTimeoutGetErrorThrow(): void
    {
        // Arrange
        $runner = new \IKEA\Tradfri\Helper\CommandRunner();

        // Act
        $this->expectExceptionMessage('Unknown error');
        $result = $runner->execWithTimeout(cmd: 'asdjalgualg', timeout: 1, asArray: false, throw: true);

        // Assert
        $this->assertSame(expected: 'Unknown error', actual: $result);
    }
}
