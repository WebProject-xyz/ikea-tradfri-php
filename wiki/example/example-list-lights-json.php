<?php
declare(strict_types=1);

require __DIR__.'/init.php';

try {
    $lights = $api->getLights();
    $lights->sortByState();
    header('Content-Type: application/json');
    echo json_encode($lights->jsonSerialize());
} catch (\Exception $e) {
    echo $e->getMessage().PHP_EOL.PHP_EOL;
    echo $e->getTraceAsString();
    die();
}
