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

require __DIR__ . '/init.php';

try {
    $lights = $api->getLights();
    $lights->sortByState();
    \header('Content-Type: application/json');
    echo \json_encode($lights->jsonSerialize(), \JSON_THROW_ON_ERROR);
} catch (Exception $e) {
    echo $e->getMessage() . \PHP_EOL . \PHP_EOL;
    echo $e->getTraceAsString();
    exit;
}
