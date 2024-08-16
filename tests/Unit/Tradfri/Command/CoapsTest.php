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

namespace IKEA\Tests\Unit\Tradfri\Command;

use IKEA\Tradfri\Command\Coap\Blinds\BlindsGetCurrentPositionCommand;
use IKEA\Tradfri\Command\Coap\Group\GroupDimmerCommand;
use IKEA\Tradfri\Command\Coap\Group\GroupSwitchStateCommand;
use IKEA\Tradfri\Command\Coap\Light\LightChangeLightTemperatureCommand;
use IKEA\Tradfri\Command\Coap\Light\LightDimmerCommand;
use IKEA\Tradfri\Command\Coap\Light\LightSwitchStateCommand;
use IKEA\Tradfri\Command\Get;
use IKEA\Tradfri\Command\Post;
use IKEA\Tradfri\Command\Put;
use IKEA\Tradfri\Command\Request;
use IKEA\Tradfri\Dto\CoapGatewayAuthConfigDto;
use IKEA\Tradfri\Helper\CommandRunner;
use IKEA\Tradfri\Helper\CommandRunnerInterface;
use PHPUnit\Framework\TestCase;

final class CoapsTest extends TestCase
{
    public function testGetRollerBlindDarkenedStateCommand(): void
    {
        // Arrange
        // Act
        $commandString = new BlindsGetCurrentPositionCommand($this->getGatewayAuthConfigDto(), 1, 100);

        // Assert
        $this->assertSame(
            'coap-client -m put -u "mocked-user" -k "mocked-api-key" -e \'{ "15015": [{ "5536": 100 }] }\' "coaps://127.0.0.1:5684/15001/1"',
            (string) $commandString,
        );
    }

    public function testGetGroupDimmerCommand(): void
    {
        // Arrange
        // Act
        $commandString = new GroupDimmerCommand($this->getGatewayAuthConfigDto(), 1, 100);

        // Assert
        $this->assertSame(
            'coap-client -m put -u "mocked-user" -k "mocked-api-key" -e \'{ "5851": 255 }\' "coaps://127.0.0.1:5684/15004/1"',
            (string) $commandString,
        );
    }

    public function testGetCoapsCommandGet(): void
    {
        // Arrange
        // Act
        $commandString = (new Get($this->getGatewayAuthConfigDto()))
            ->requestCommand(Request::RootDevices);

        // Assert
        $this->assertSame(
            'coap-client -m get -u "mocked-user" -k "mocked-api-key" "coaps://127.0.0.1:5684/15001"',
            $commandString,
        );
    }

    public function testGetLightColorCommandWarm(): void
    {
        // Arrange
        // Act
        $commandString = new LightChangeLightTemperatureCommand($this->getGatewayAuthConfigDto(), 1, \IKEA\Tradfri\Dto\CoapGatewayRequestPayloadDto::COLOR_WARM);

        // Assert
        $this->assertSame(
            'coap-client -m put -u "mocked-user" -k "mocked-api-key" -e \'{ "3311": [{ "5709": 33135, "5710": 27211 }] }\' "coaps://127.0.0.1:5684/15001/1"',
            (string) $commandString,
        );
    }

    public function testGetLightColorCommandCold(): void
    {
        // Arrange
        // Act
        $commandString = new LightChangeLightTemperatureCommand($this->getGatewayAuthConfigDto(), 1, \IKEA\Tradfri\Dto\CoapGatewayRequestPayloadDto::COLOR_COLD);

        // Assert
        $this->assertSame(
            'coap-client -m put -u "mocked-user" -k "mocked-api-key" -e \'{ "3311": [{ "5709": 24930, "5710": 24684 }] }\' "coaps://127.0.0.1:5684/15001/1"',
            (string) $commandString,
        );
    }

    public function testGetLightColorCommandNormal(): void
    {
        // Arrange
        // Act
        $commandString = new LightChangeLightTemperatureCommand($this->getGatewayAuthConfigDto(), 1, \IKEA\Tradfri\Dto\CoapGatewayRequestPayloadDto::COLOR_NORMAL);

        // Assert
        $this->assertSame(
            'coap-client -m put -u "mocked-user" -k "mocked-api-key" -e \'{ "3311": [{ "5709": 30140, "5710": 26909 }] }\' "coaps://127.0.0.1:5684/15001/1"',
            (string) $commandString,
        );
    }

    public function testGetGroupSwitchCommand(): void
    {
        // Arrange
        // Act
        $commandString = new GroupSwitchStateCommand($this->getGatewayAuthConfigDto(), 2, true);

        // Assert
        $this->assertSame(
            'coap-client -m put -u "mocked-user" -k "mocked-api-key" -e \'{ "5850": 1 }\' "coaps://127.0.0.1:5684/15004/2"',
            (string) $commandString,
        );
    }

    public function testGetCoapsCommandPost(): void
    {
        // Arrange
        // Act
        $commandString = (new Post($this->getGatewayAuthConfigDto()))
            ->requestCommand(Request::RootDevices, 'injected');

        // Assert
        $this->assertSame(
            'coap-client -m post -u "mocked-user" -k "mocked-api-key" injected "coaps://127.0.0.1:5684/15001"',
            $commandString,
        );
    }

    public function testGetLightSwitchCommand(): void
    {
        // Arrange
        // Act
        $commandString = new LightSwitchStateCommand($this->getGatewayAuthConfigDto(), 111, true);

        // Assert
        $this->assertSame(
            'coap-client -m put -u "mocked-user" -k "mocked-api-key" -e \'{ "3311": [{ "5850": 1 }] }\' "coaps://127.0.0.1:5684/15001/111"',
            (string) $commandString,
        );
    }

    public function testGetLightDimmerCommand(): void
    {
        // Arrange
        // Act
        $commandString = new LightDimmerCommand($this->getGatewayAuthConfigDto(), 111, 50);

        // Assert
        $this->assertSame(
            'coap-client -m put -u "mocked-user" -k "mocked-api-key" -e \'{ "3311": [{ "5851": 128 }] }\' "coaps://127.0.0.1:5684/15001/111"',
            (string) $commandString,
        );
    }

    public function testGetCoapsCommandPut(): void
    {
        // Arrange
        // Act
        $commandString = (new Put($this->getGatewayAuthConfigDto()))->requestCommand(Request::RootDevices, 'injected');

        // Assert
        $this->assertSame(
            'coap-client -m put -u "mocked-user" -k "mocked-api-key" injected "coaps://127.0.0.1:5684/15001"',
            $commandString,
        );
    }

    public function testGetPreSharedKeyCommand(): void
    {
        // Arrange
        $coaps = $this->buildCoapsCommandsWrapper();

        // Act
        $commandString = $coaps->getPreSharedKeyCommand();

        // Assert
        $this->assertSame(
            'coap-client -m post -u "Client_identity" -k "mocked-secret" -e \'{"9090":"mocked-user"}\' "coaps://127.0.0.1:5684/15011/9063"',
            $commandString,
        );
    }

    public function testGetSharedKeyFromGateway(): void
    {
        // Arrange
        $runner = \Mockery::mock(CommandRunnerInterface::class);
        $runner->expects('execWithTimeout')->andReturn(['mocked-shared-key']);

        $coaps = $this->buildCoapsCommandsWrapper($runner);

        // Act
        $sharedKey = $coaps->getSharedKeyFromGateway();

        // Assert
        $this->assertSame(
            'mocked-shared-key',
            $sharedKey,
        );
    }

    private function buildCoapsCommandsWrapper(?CommandRunnerInterface $runner = null): \IKEA\Tradfri\Command\GatewayHelperCommands
    {
        return new \IKEA\Tradfri\Command\GatewayHelperCommands(
            $this->getGatewayAuthConfigDto(),
            $runner ?? new CommandRunner(),
        );
    }

    private function getGatewayAuthConfigDto(): CoapGatewayAuthConfigDto
    {
        return new CoapGatewayAuthConfigDto(
            username: 'mocked-user',
            apiKey: 'mocked-api-key',
            gatewaySecret: 'mocked-secret',
            gatewayIp: '127.0.0.1',
        );
    }
}
