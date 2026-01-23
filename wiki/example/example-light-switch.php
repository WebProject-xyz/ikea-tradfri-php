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

use IKEA\Tradfri\Device\LightBulb;

require __DIR__ . '/init.php';

try {
    echo '---------- IKEA Tradfri PHP API Example: ' . \basename(__FILE__) . \PHP_EOL;
    /** @var \IKEA\Tradfri\Service\ServiceInterface $api */
    $lights = $api->getLights();

    $lights->sortByState();
    if (false ===$lights->isEmpty()) {
        /** @var LightBulb $light */
        $light = $lights->findFirst(
            static fn ($light) =>
                /** @var LightBulb $light */
                'Wohnzimmer - Schreibtisch' === $light->getName(),
        );
        echo '---------- Light Information' . \PHP_EOL;
        echo ' ' . \PHP_EOL;
        echo '- ID: ' . $light->getId() . \PHP_EOL;
        echo '- Name: ' . $light->getName() . \PHP_EOL;
        echo '- Manufacturer: ' . $light->getManufacturer() . \PHP_EOL;
        echo '- Version: ' . $light->getVersion() . \PHP_EOL;
        echo '- State is: ' . $light->getReadableState() . \PHP_EOL;
        echo '- Brightness ' . $light->getBrightness() . '%' . \PHP_EOL;
        echo ' ' . \PHP_EOL;
        echo '---------- Change State' . \PHP_EOL;
        if ($light->isOn()) {
            if ($light->switchOff()) {
                echo 'switched off' . \PHP_EOL;
            }
        } else {
            if ($light->switchOn()) {
                echo 'switched on' . \PHP_EOL;
            }
        }

        echo 'light is now ' . $light->getReadableState() . \PHP_EOL;

        return true;
    }
} catch (Exception $e) {
    echo \PHP_EOL . '---------- Error';
    echo \PHP_EOL . $e->getMessage() . \PHP_EOL . \PHP_EOL;
    echo $e->getTraceAsString();
    exit;
}
