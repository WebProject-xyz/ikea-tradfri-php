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
use IKEA\Tradfri\Command\Post;
use IKEA\Tradfri\Command\Request;
use IKEA\Tradfri\Dto\CoapGatewayAuthConfigDto;
use IKEA\Tradfri\Helper\CommandRunnerInterface;

final class PostTest extends Unit
{
    protected UnitTester $tester;

    public function testICanConstruct(): void
    {
        // Arrange
        $authConfig = new CoapGatewayAuthConfigDto('user', 'key', 'secret', '127.0.0.1');

        // Act
        $command = new Post($authConfig);

        // Assert
        $this->assertInstanceOf(Post::class, $command);
    }

    public function testICanGetRequestCommand(): void
    {
        // Arrange
        $authConfig = new CoapGatewayAuthConfigDto('user', 'key', 'secret', '127.0.0.1');

        $command = new Post($authConfig);
        $request = Request::RootGateway;

        // Act
        $result = $command->requestCommand($request, 'payload');

        // Assert
        $this->assertStringContainsString('coap-client -m post', $result);
        $this->assertStringContainsString('payload', $result);
        $this->assertStringContainsString('127.0.0.1', $result);
        $this->assertStringContainsString((string) $request->value, $result);
    }

    public function testICanRunCommand(): void
    {
        // Arrange
        $authConfig = new CoapGatewayAuthConfigDto('user', 'key', 'secret', '127.0.0.1');

        $runner = \Mockery::mock(CommandRunnerInterface::class);
        $runner->shouldReceive('execWithTimeout')->andReturn(['']);

        $command = new Post($authConfig);

        // Act
        $result = $command->run($runner);

        // Assert
        $this->assertTrue($result);
    }

    public function testToString(): void
    {
        // Arrange
        $authConfig = new CoapGatewayAuthConfigDto('user', 'key', 'secret', '127.0.0.1');

        $command = new Post($authConfig);

        // Act
        $string = (string) $command;

        // Assert
        $this->assertStringContainsString('coap-client -m post', $string);
        $this->assertStringContainsString('user', $string);
        $this->assertStringContainsString('key', $string);
    }
}
