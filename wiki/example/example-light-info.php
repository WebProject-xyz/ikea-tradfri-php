<?php
declare(strict_types=1);

require __DIR__.'/init.php';

try {
    echo '---------- IKEA Tradfri PHP API Example: '.basename(__FILE__).PHP_EOL;

    $lights = $api->getLights();

    $lights->sortByState();
    if (false ===$lights->isEmpty()) {
        $light = $lights->first();
        echo 'Light:'.PHP_EOL;
        echo 'ID: ' . $light->getId(). PHP_EOL;
        echo 'Name: ' . $light->getName(). PHP_EOL;
        echo 'Manufacturer: ' . $light->getManufacturer(). PHP_EOL;
        echo 'Version: ' . $light->getVersion(). PHP_EOL;
        echo 'State is: ' . $light->getState(). PHP_EOL;
        echo 'Brightness ' . $light->getBrightness().'%'. PHP_EOL;
        return true;
    }
    die('no lights connected to hub');
} catch (\Exception $e) {
    echo $e->getMessage().PHP_EOL.PHP_EOL;
    echo $e->getTraceAsString();
    die();
}
