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

require __DIR__ . '/init.php';

try {
    /** @var \IKEA\Tradfri\Service\ServiceInterface $api */
    $devices = $api->getDevices();

    echo '---------- IKEA Tradfri PHP API Example: ' . \basename(__FILE__) . \PHP_EOL;
    $devices->forAll(static function ($key, $device) {
        /** @var IKEA\Tradfri\Device\Device $device */
        echo '---------- Device Information' . \PHP_EOL;
        echo '- ID: ' . $device->getId() . \PHP_EOL;
        echo '- Type: ' . $device->getType() . \PHP_EOL;
        echo '- Name: ' . $device->getName() . \PHP_EOL;
        echo '- Manufacturer: ' . $device->getManufacturer() . \PHP_EOL;
        echo '- Version: ' . $device->getVersion() . \PHP_EOL;
        if ($device instanceof IKEA\Tradfri\Device\LightBulb) {
            echo '- State is: ' . $device->getReadableState() . \PHP_EOL;
            echo '- Brightness ' . $device->getBrightness() . '%' . \PHP_EOL;
            echo '- Color HEX #' . $device->getColor() . '' . \PHP_EOL;
        }

        echo ' ' . \PHP_EOL;

        return true;
    });

    echo \var_export(\json_encode($devices->jsonSerialize(), \JSON_THROW_ON_ERROR | \JSON_PRETTY_PRINT), true);
} catch (Exception $e) {
    echo $e->getMessage() . \PHP_EOL . \PHP_EOL;
    echo $e->getTraceAsString();
    exit;
}
