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

namespace IKEA\Tests\Unit\Tradfri\Service;

use Codeception\Test\Unit as UnitTest;
use IKEA\Tradfri\Client\ClientInterface as Client;
use IKEA\Tradfri\Collection\Devices;
use IKEA\Tradfri\Collection\Groups;
use IKEA\Tradfri\Collection\LightBulbs;
use IKEA\Tradfri\Command\Coap\Keys;
use IKEA\Tradfri\Device\Feature\BrightnessStateInterface;
use IKEA\Tradfri\Device\Feature\DeviceInterface;
use IKEA\Tradfri\Device\Feature\SwitchableInterface;
use IKEA\Tradfri\Device\LightBulb;
use IKEA\Tradfri\Device\RollerBlind;
use IKEA\Tradfri\Exception\RuntimeException;
use IKEA\Tradfri\Group\DeviceGroup as Group;
use IKEA\Tradfri\Service\GatewayApiService;
use IKEA\Tradfri\Values\DeviceType;

final class ApiTest extends UnitTest
{
    public function testIGotAnInstanceOfApiService(): void
    {
        // Arrange

        $client = \Mockery::mock(Client::class);
        // Act
        $service = new GatewayApiService($client);
        // Assert
        $this->assertInstanceOf(GatewayApiService::class, $service);
    }

    public function testICanGetDevicesCollectionFromService(): void
    {
        // Arrange

        $client = \Mockery::mock(Client::class);
        $client->expects('getDevices')->andReturn(new Devices());
        $service = new GatewayApiService($client);

        // Act
        $result = $service->getDevices();

        // Assert
        $this->assertInstanceOf(Devices::class, $result);
    }

    public function testICanGetLightblubsCollectionFromService(): void
    {
        // Arrange

        $client = \Mockery::mock(Client::class);
        $client->expects('getDevices')->andReturn(new Devices());

        $service = new GatewayApiService($client);

        // Act
        $result = $service->getLights();

        // Assert
        $this->assertInstanceOf(Devices::class, $result);
        $this->assertInstanceOf(LightBulbs::class, $result);
    }

    public function testICanSwitchLightOff(): void
    {
        // Arrange

        $client = \Mockery::mock(Client::class);
        $client->expects('lightOff')->andReturn(true);

        $service = new GatewayApiService($client);

        $lightBulb = new LightBulb(1, Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_WS);
        $lightBulb->setState(true);

        $this->assertTrue($lightBulb->isOn());
        // Act
        $result = $service->off($lightBulb);
        // Assert
        $this->assertTrue($result);
    }

    public function testICanNotSwitchLightOff(): void
    {
        // Arrange

        $client = \Mockery::mock(Client::class);
        $client->expects('lightOff')->andReturn(false);

        $service = new GatewayApiService($client);

        $lightBulb = new LightBulb(1, Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_WS);
        $lightBulb->setState(true);

        $this->assertTrue($lightBulb->isOn());
        // Act
        $result = $service->off($lightBulb);
    }

    public function testICanNotSwitchLightOffBecauseItIsOff(): void
    {
        // Arrange
        $lightBulb = new LightBulb(1, Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_WS);
        $lightBulb->setState(false);

        $client = \Mockery::mock(Client::class);
        $client->expects('lightOff')->andReturn(true);
        $service = new GatewayApiService($client);

        $this->assertFalse($lightBulb->isOn());

        // Act
        $result = $service->off($lightBulb);
        // Assert
        $this->assertTrue($result);
    }

    public function testICanSwitchLightOn(): void
    {
        // Arrange
        $lightBulb = new LightBulb(1, Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_WS);
        $lightBulb->setState(false);

        $client = \Mockery::mock(Client::class);
        $client->expects('lightOn')->andReturn(true);
        $service = new GatewayApiService($client);

        $this->assertFalse($lightBulb->isOn());

        // Act
        $result = $service->on($lightBulb);

        // Assert
        $this->assertTrue($result);
    }

    public function testICanNotSwitchLightOn(): void
    {
        // Assert
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('unable to change state of lightBulb: 1');

        // Arrange
        $lightBulb = new LightBulb(1, Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_WS);
        $lightBulb->setState(false);

        $client = \Mockery::mock(Client::class);
        $client->expects('lightOn')->andThrow(new RuntimeException('unable to change state of lightBulb: 1'));
        $service = new GatewayApiService($client);

        $this->assertFalse($lightBulb->isOn());

        // Act
        $result = $service->on($lightBulb);
    }

    public function testICanNotSwitchLightOnBecauseItIsOn(): void
    {
        // Arrange
        $lightBulb = new LightBulb(1, Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_WS);
        $lightBulb->setState(true);

        $client = \Mockery::mock(Client::class);
        $client->expects('lightOn')->andReturn(true);
        $service = new GatewayApiService($client);

        $this->assertTrue($lightBulb->isOn());

        // Act
        $result = $service->on($lightBulb);
        // Assert
        $this->assertTrue($result);
    }

    public function testICanSwitchAllLightsOff(): void
    {
        // Arrange
        $lightBulb = new LightBulb(1, Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_WS);
        $lightBulb->setState(true);

        $lightBulbs = new LightBulbs();
        $lightBulbs->addDevice(clone $lightBulb);
        $lightBulbs->addDevice((clone $lightBulb)->setId(2));

        $client = \Mockery::mock(Client::class);
        $client->expects('lightOff')->times(2)->andReturn(true);
        $service = new GatewayApiService($client);

        // Act
        $result = $service->allLightsOff($lightBulbs);

        // Assert
        $this->assertTrue($result);
    }

    public function testICanNotSwitchGroupOnBecauseItIsOn(): void
    {
        // Arrange

        $client = \Mockery::mock(Client::class);
        $client->expects('groupOn')->andReturn(true);
        $service = new GatewayApiService($client);

        $group = new Group(1, $service);
        $group->setState(true);
        $group->getDevices()
            ->addDevice(
                (new LightBulb(2, Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_W)
            )
                ->setState(true)
                ->setName('test'),
            );

        $this->assertTrue($group->isOn());

        // Act
        $result = $service->on($group);
        // Assert
        $this->assertTrue($result);
    }

    public function testICanSwitchGroupOn(): void
    {
        // Arrange

        $client = \Mockery::mock(Client::class);
        $client->expects('groupOn')->andReturn(true);
        $service = new GatewayApiService($client);

        $group = new Group(1, $service);
        $group->getDevices()->addDevice((new LightBulb(2, Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_W))
            ->setState(false)
            ->setName('test'));

        $this->assertFalse($group->isOn());

        // Act
        $result = $service->on($group);
        // Assert
        $this->assertTrue($result);
    }

    public function testICanSwitchGroupOff(): void
    {
        // Arrange

        $client = \Mockery::mock(Client::class);
        $client->expects('groupOff')->andReturn(true);
        $service = new GatewayApiService($client);
        $group   = new Group(1, $service);

        $group->getDevices()->addDevice(
            (new LightBulb(2, Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_W))
                ->setState(true)
                ->setName('test'),
        );

        $this->assertTrue($group->isOn());

        // Act
        $result = $service->off($group);
        // Assert
        $this->assertTrue($result);
    }

    public function testICanDimAGroup(): void
    {
        // Arrange

        $client = \Mockery::mock(Client::class);
        $client->expects('dimGroup')->andReturn(true);

        $service = new GatewayApiService($client);
        $group   = new Group(1, $service);
        // Act
        $result = $service->dim($group, 20);
        // Assert
        $this->assertTrue($result);
    }

    public function testICanDimError(): void
    {
        // Arrange

        $client = \Mockery::mock(Client::class);

        $service = new GatewayApiService($client);
        $group   = new class() implements BrightnessStateInterface, DeviceInterface {
            public function dim(int $levelInPercent): bool
            {
                return false;
            }

            public function setBrightnessLevel(float|int $levelInPercent): void
            {
            }

            public function setBrightness(int $brightness): void
            {
            }

            public function getBrightness(): float
            {
                return 0.0;
            }

            public function getId(): int
            {
                return 5;
            }

            public function getName(): string
            {
                return 'Implement getName() method.';
            }

            public function getType(): string
            {
                return 'Implement getType() method.';
            }

            public function getTypeEnum(): DeviceType
            {
                return DeviceType::BLUB;
            }

            public function jsonSerialize(): array
            {
                return [];
            }
        };
        // Act
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('invalid device type: Implement getType() method.');

        $result = $service->dim($group, 20);
        // Assert
        $this->assertTrue($result);
    }

    public function testICanOnTypeError(): void
    {
        // Arrange

        $client = \Mockery::mock(Client::class);

        $service = new GatewayApiService($client);
        $group   = new class() implements SwitchableInterface {
            public function getId(): int
            {
                return 5;
            }

            public function getName(): string
            {
                return 'Implement getName() method.';
            }

            public function getType(): string
            {
                return 'Implement getType() method.';
            }

            public function getTypeEnum(): DeviceType
            {
                return DeviceType::BLUB;
            }

            public function jsonSerialize(): array
            {
                return [];
            }

            public function getReadableState(): string
            {
                return 'false';
            }

            public function setState(bool $state): static
            {
                return $this;
            }

            public function isOn(): bool
            {
                return false;
            }

            public function isOff(): bool
            {
                return false;
            }

            public function switchOn(): bool
            {
                return false;
            }

            public function switchOff(): bool
            {
                return false;
            }
        };
        // Act
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('invalid device type: Implement getType() method.');

        $result = $service->on($group);
        // Assert
        $this->assertTrue($result);
    }

    public function testICanOffTypeError(): void
    {
        // Arrange

        $client = \Mockery::mock(Client::class);

        $service = new GatewayApiService($client);
        $group   = new class() implements SwitchableInterface {
            public function getId(): int
            {
                return 5;
            }

            public function getName(): string
            {
                return 'Implement getName() method.';
            }

            public function getType(): string
            {
                return 'Implement getType() method.';
            }

            public function getTypeEnum(): DeviceType
            {
                return DeviceType::BLUB;
            }

            public function jsonSerialize(): array
            {
                return [];
            }

            public function getReadableState(): string
            {
                return 'false';
            }

            public function setState(bool $state): static
            {
                return $this;
            }

            public function isOn(): bool
            {
                return false;
            }

            public function isOff(): bool
            {
                return false;
            }

            public function switchOn(): bool
            {
                return false;
            }

            public function switchOff(): bool
            {
                return false;
            }
        };
        // Act
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('invalid device type: Implement getType() method.');

        $result = $service->off($group);
        // Assert
        $this->assertTrue($result);
    }

    public function testICanDimALight(): void
    {
        // Arrange
        $lightBulb = new LightBulb(1, Keys::ATTR_DEVICE_INFO_TYPE_BLUB_E27_W);

        $client = \Mockery::mock(Client::class);
        $client->expects('dimLight')->andReturn(true);

        $service = new GatewayApiService($client);
        // Act
        $result = $service->dim($lightBulb, 20);
        // Assert
        $this->assertTrue($result);
    }

    public function testICanNotSetBlindPosition(): void
    {
        // Arrange
        $rollerBlind = new RollerBlind(1, Keys::ATTR_DEVICE_INFO_TYPE_ROLLER_BLIND);

        $client  = \Mockery::mock(Client::class);
        $client->expects()->setRollerBlindPosition($rollerBlind, 20)->andReturnUsing(static function () use ($rollerBlind) {
            $rollerBlind->setDarkenedState(20);

            return true;
        });
        $service = new GatewayApiService($client);
        // Act
        $result = $service->setRollerBlindPosition($rollerBlind, 20);

        // Assert
        $this->assertTrue($result);
        $this->assertFalse($rollerBlind->isFullyClosed());
        $this->assertFalse($rollerBlind->isFullyOpened());
        $this->assertSame(20, $rollerBlind->getDarkenedState());
    }

    public function testICanGetGroupsFromService(): void
    {
        // Arrange

        $client = \Mockery::mock(Client::class);
        $client->expects('getGroups')->andReturn(new Groups());
        $service = new GatewayApiService($client);

        // Act
        $groups = $service->getGroups();

        // Assert
        $this->assertInstanceOf(Groups::class, $groups);
        $this->assertTrue($groups->isEmpty());
        $this->assertCount(0, $groups);
    }
}
