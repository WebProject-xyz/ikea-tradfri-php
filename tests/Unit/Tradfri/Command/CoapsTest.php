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

use IKEA\Tradfri\Command\Coaps;
use IKEA\Tradfri\Helper\CommandRunner;
use PHPUnit\Framework\TestCase;

final class CoapsTest extends TestCase
{
    public function testGetRollerBlindDarkenedStateCommand(): void
    {
        // Arrange
        $coaps = new Coaps('127.0.0.1', 'secret', 'apiKey', 'username');

        // Act
        $commandString = $coaps->getRollerBlindDarkenedStateCommand(1, 100);

        // Assert
        $this->assertSame(
            'coap-client -m put -u "username" -k "apiKey" -e \'{ "15015": [{ "5536": 100 }] }\'  "coaps://127.0.0.1:5684/15001/1"',
            $commandString,
        );
    }

    public function testGetGroupDimmerCommand(): void
    {
        // Arrange
        $coaps = new Coaps('127.0.0.1', 'secret', 'apiKey', 'username');

        // Act
        $commandString = $coaps->getGroupDimmerCommand(1, 100);

        // Assert
        $this->assertSame(
            'coap-client -m put -u "username" -k "apiKey" -e \'{ "5851": 255 }\'  "coaps://127.0.0.1:5684/15004/1"',
            $commandString,
        );
    }

    public function testGetCoapsCommandGet(): void
    {
        // Arrange
        $coaps = new Coaps('127.0.0.1', 'secret', 'apiKey', 'username');

        // Act
        $commandString = $coaps->getCoapsCommandGet(\IKEA\Tradfri\Command\Coap\Keys::ROOT_DEVICES);

        // Assert
        $this->assertSame(
            'coap-client -m get -u "username" -k "apiKey" "coaps://127.0.0.1:5684/15001"',
            $commandString,
        );
    }

    public function testGetLightColorCommand(): void
    {
        // Arrange
        $coaps = new Coaps('127.0.0.1', 'secret', 'apiKey', 'username');

        // Act
        $commandString = $coaps->getLightColorCommand(1, 'normal');

        // Assert
        $this->assertSame(
            'coap-client -m put -u "username" -k "apiKey" -e \'{ "3311": [{ "5709": 30140, "5710": 26909 }] }\'  "coaps://127.0.0.1:5684/15001/1"',
            $commandString,
        );
    }

    public function testGetGroupSwitchCommand(): void
    {
        // Arrange
        $coaps = new Coaps('127.0.0.1', 'secret', 'apiKey', 'username');

        // Act
        $commandString = $coaps->getGroupSwitchCommand(2, true);

        // Assert
        $this->assertSame(
            'coap-client -m put -u "username" -k "apiKey" -e \'{ "5850": 1 }\'  "coaps://127.0.0.1:5684/15004/2"',
            $commandString,
        );
    }

    public function testGetCoapsCommandPost(): void
    {
        // Arrange
        $coaps = new Coaps('127.0.0.1', 'secret', 'apiKey', 'username');

        // Act
        $commandString = $coaps->getCoapsCommandPost(\IKEA\Tradfri\Command\Coap\Keys::ROOT_DEVICES, 'injected');

        // Assert
        $this->assertSame(
            'coap-client -m post -u "username" -k "apiKey"injected "coaps://127.0.0.1:5684/15001"',
            $commandString,
        );
    }

    public function testGetLightSwitchCommand(): void
    {
        // Arrange
        $coaps = new Coaps('127.0.0.1', 'secret', 'apiKey', 'username');

        // Act
        $commandString = $coaps->getLightSwitchCommand(111, true);

        // Assert
        $this->assertSame(
            'coap-client -m put -u "username" -k "apiKey" -e \'{ "3311": [{ "5850": 1 }] }\'  "coaps://127.0.0.1:5684/15001/111"',
            $commandString,
        );
    }

    public function testGetLightDimmerCommand(): void
    {
        // Arrange
        $coaps = new Coaps('127.0.0.1', 'secret', 'apiKey', 'username');

        // Act
        $commandString = $coaps->getLightDimmerCommand(111, 50);

        // Assert
        $this->assertSame(
            'coap-client -m put -u "username" -k "apiKey" -e \'{ "3311": [{ "5851": 128 }] }\'  "coaps://127.0.0.1:5684/15001/111"',
            $commandString,
        );
    }

    public function testGetCoapsCommandPut(): void
    {
        // Arrange
        $coaps = new Coaps('127.0.0.1', 'secret', 'apiKey', 'username');

        // Act
        $commandString = $coaps->getCoapsCommandPut(\IKEA\Tradfri\Command\Coap\Keys::ROOT_DEVICES, 'injected');

        // Assert
        $this->assertSame(
            'coap-client -m put -u "username" -k "apiKey"injected "coaps://127.0.0.1:5684/15001"',
            $commandString,
        );
    }

    public function testGetPreSharedKeyCommand(): void
    {
        // Arrange
        $coaps = new Coaps('127.0.0.1', 'secret', 'apiKey', 'username');

        // Act
        $commandString = $coaps->getPreSharedKeyCommand();

        // Assert
        $this->assertSame(
            'coap-client -m post -u "Client_identity" -k "secret" -e \'{"9090":"username"}\' "coaps://127.0.0.1:5684/15011/9063"',
            $commandString,
        );
    }

    public function testGetSharedKeyFromGateway(): void
    {
        // Arrange
        $runner = mock(CommandRunner::class);
        $runner->expects('execWithTimeout')->andReturn(['mocked-shared-key']);

        $coaps = new Coaps(
            '127.0.0.1',
            'secret',
            'apiKey',
            'username',
            $runner,
        );

        // Act
        $sharedKey = $coaps->getSharedKeyFromGateway();

        // Assert
        $this->assertSame(
            'mocked-shared-key',
            $sharedKey,
        );
    }
}
