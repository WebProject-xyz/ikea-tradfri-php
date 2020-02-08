<?php
declare(strict_types=1);

require __DIR__.'/init.php';

try {
    echo '---------- IKEA Tradfri PHP API Example: '.basename(__FILE__).PHP_EOL;

    $lights = $api->getLights();

    $lights->sortByState();
    echo '---------- IKEA Tradfri PHP API Example: '.basename(__FILE__).PHP_EOL;
    $lights->forAll(
        function ($key, $light) {
            /** @var \IKEA\Tradfri\Device\Lightbulb $light */
            echo '---------- Light Information'.PHP_EOL;
            echo '- ID: ' . $light->getId(). PHP_EOL;
            echo '- Name: ' . $light->getName(). PHP_EOL;
            echo '- Manufacturer: ' . $light->getManufacturer(). PHP_EOL;
            echo '- Version: ' . $light->getVersion(). PHP_EOL;
            echo '- State is: ' . $light->getReadableState(). PHP_EOL;
            echo '- Brightness ' . $light->getBrightness().'%'. PHP_EOL;
            echo ' '.PHP_EOL;

            return true;
        }
    );
} catch (\Exception $e) {
    echo $e->getMessage().PHP_EOL.PHP_EOL;
    echo $e->getTraceAsString();
    die();
}
