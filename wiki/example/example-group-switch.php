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

require __DIR__ . '/init.php';

try {
    echo '---------- IKEA Tradfri PHP API Example: ' . \basename(__FILE__) . \PHP_EOL;

    $groups = $api->getGroups();

    if ($groups->isEmpty() === false) {
        $group= $groups->first();
        echo '---------- Group Information' . \PHP_EOL;
        echo '- ID: ' . $group->getId() . \PHP_EOL;
        echo '- Name: ' . $group->getName() . \PHP_EOL;

        $lights = $group->getLights();

        if (false === $lights->isEmpty()) {
            $light = $lights->first();
            $lights->forAll(static function ($key, $light) {
                /** @var IKEA\Tradfri\Device\LightBulb $light */
                echo '---------- Light Information' . \PHP_EOL;
                echo '- ID: ' . $light->getId() . \PHP_EOL;
                echo '- Name: ' . $light->getName() . \PHP_EOL;
                echo '- Manufacturer: ' . $light->getManufacturer() . \PHP_EOL;
                echo '- Version: ' . $light->getVersion() . \PHP_EOL;
                echo '- State is: ' . $light->getReadableState() . \PHP_EOL;
                echo '- Brightness ' . $light->getBrightness() . '%' . \PHP_EOL;
                echo ' ' . \PHP_EOL;

                return true;
            });
        }

        echo '---------- Switch Group' . \PHP_EOL;
        echo 'group is: ' . ($group->isOn() ? 'on' : 'off') . \PHP_EOL;
        if ($group->isOn()) {
            if ($group->off()) {
                echo 'switched off' . \PHP_EOL;
            }
        } else {
            if ($group->switchOn()) {
                echo 'switched on' . \PHP_EOL;
            }
        }

        echo 'group is now ' . ($group->isOn() ? 'on' : 'off') . \PHP_EOL;

        return true;
    }
} catch (Exception $e) {
    echo \PHP_EOL . '---------- Error';
    echo \PHP_EOL . $e->getMessage() . \PHP_EOL . \PHP_EOL;
    echo $e->getTraceAsString();
    exit;
}
