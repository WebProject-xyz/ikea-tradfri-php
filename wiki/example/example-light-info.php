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

echo '---------- IKEA Tradfri PHP API Example: ' . \basename(__FILE__) . \PHP_EOL;
try {
    /** @var \IKEA\Tradfri\Service\ServiceInterface $api */
    $lights = $api->getLights();

    $lights->sortByState();
    if (false ===$lights->isEmpty()) {
        $light = $lights->first();
        echo 'Light:' . \PHP_EOL;
        echo 'ID: ' . $light->getId() . \PHP_EOL;
        echo 'Name: ' . $light->getName() . \PHP_EOL;
        echo 'Manufacturer: ' . $light->getManufacturer() . \PHP_EOL;
        echo 'Version: ' . $light->getVersion() . \PHP_EOL;
        echo 'State is: ' . $light->getReadableState() . \PHP_EOL;
        echo 'Brightness ' . $light->getBrightness() . '%' . \PHP_EOL;

        return true;
    }

    exit('no lights connected to hub');
} catch (Exception $e) {
    echo $e->getMessage() . \PHP_EOL . \PHP_EOL;
    echo $e->getTraceAsString();
    exit;
}
