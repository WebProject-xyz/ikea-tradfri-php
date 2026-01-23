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

namespace IKEA\Tests\Unit\Tradfri\Command;

use Codeception\Test\Unit;
use IKEA\Tests\Support\UnitTester;
use IKEA\Tradfri\Command\Get;
use IKEA\Tradfri\Command\Request;
use IKEA\Tradfri\Dto\CoapGatewayAuthConfigDto;
use IKEA\Tradfri\Helper\CommandRunnerInterface;

final class GetTest extends Unit
{
    protected UnitTester $tester;

    public function testICanConstruct(): void
    {
        // Arrange
        $authConfig = new CoapGatewayAuthConfigDto('user', 'key', 'secret', '127.0.0.1');

        // Act
        $command = new Get($authConfig);

        // Assert
        $this->assertInstanceOf(Get::class, $command);
    }

    public function testRunThrowsOnMissingTarget(): void
    {
        // Arrange
        $authConfig = new CoapGatewayAuthConfigDto('user', 'key', 'secret', '127.0.0.1');
        $runner     = \Mockery::mock(CommandRunnerInterface::class);
        $command    = new Get($authConfig);

        // Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('missing target');

        // Act
        $command->run($runner);
    }

    public function testRunSuccess(): void
    {
        // Arrange
        $authConfig = new CoapGatewayAuthConfigDto('user', 'key', 'secret', '127.0.0.1');
        $runner     = \Mockery::mock(CommandRunnerInterface::class);
        $runner->shouldReceive('execWithTimeout')->andReturn(['result']);

        $command = new Get($authConfig);

        // Act
        $result = $command->run($runner, Request::RootDevices);

        // Assert
        $this->assertSame(['result'], $result);
    }
}
