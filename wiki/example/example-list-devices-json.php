<?php
declare(strict_types=1);

require __DIR__.'/init.php';

try {
    echo '---------- IKEA Tradfri PHP API Example: '.basename(__FILE__).PHP_EOL;

    $devices = $api->getDevices();
    $data =$devices->jsonSerialize();
    var_dump($data);
    exit;
    echo json_encode($data);
} catch (\Exception $e) {
    echo $e->getMessage().PHP_EOL.PHP_EOL;
    echo $e->getTraceAsString();
    die();
}
