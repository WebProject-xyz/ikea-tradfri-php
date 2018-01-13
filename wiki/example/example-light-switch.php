<?php
declare(strict_types=1);

require __DIR__.'/init.php';

try {
    echo '---------- IKEA Tradfri PHP API Example: '.basename(__FILE__).PHP_EOL;

    $lights = $api->getLights();

    $lights->sortByState();
    if (false ===$lights->isEmpty()) {
        $light = $lights->first();
        echo '---------- IKEA Tradfri PHP API Example: '.basename(__FILE__).PHP_EOL;
        echo '---------- Light Information'.PHP_EOL;
        echo ' '.PHP_EOL;
        echo '- ID: ' . $light->getId(). PHP_EOL;
        echo '- Name: ' . $light->getName(). PHP_EOL;
        echo '- Manufacturer: ' . $light->getManufacturer(). PHP_EOL;
        echo '- Version: ' . $light->getVersion(). PHP_EOL;
        echo '- State is: ' . $light->getState(). PHP_EOL;
        echo '- Brightness ' . $light->getBrightness().'%'. PHP_EOL;
        echo ' '.PHP_EOL;
        echo '---------- Change State'.PHP_EOL;
        if ($light->isOn()) {
            if ($light->off()) {
                echo 'switched off'. PHP_EOL;
            }
        } else {
            if ($light->on()) {
                echo 'switched on'. PHP_EOL;
            }
        }
        echo 'light is now ' . $light->getState() . PHP_EOL;
        return true;
    }
} catch (\Exception $e) {
    echo PHP_EOL.'---------- Error';
    echo PHP_EOL. $e->getMessage().PHP_EOL.PHP_EOL;
    echo $e->getTraceAsString();
    die();
}
