<?php
declare(strict_types=1);

require __DIR__.'/init.php';

try {

    $devices = $api->getDevices();
    $data = $devices->jsonSerialize();
    header('Content-Type: application/json');
    echo json_encode($data, JSON_THROW_ON_ERROR);
} catch (\Exception $e) {
    echo $e->getMessage().PHP_EOL.PHP_EOL;
    echo $e->getTraceAsString();
    die();
}
