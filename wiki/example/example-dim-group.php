<?php
declare(strict_types=1);

require __DIR__.'/init.php';

try {
    echo '---------- IKEA Tradfri PHP API Example: '.basename(__FILE__).PHP_EOL;

    $groups = $api->getGroups();

    if ($groups->isEmpty() === false) {
        $group= $groups->first();
        echo '---------- Group Information'.PHP_EOL;
        echo '- ID: ' . $group->getId(). PHP_EOL;
        echo '- Name: ' . $group->getName(). PHP_EOL;

        $lights = $group->getLights();

        if (false === $lights->isEmpty()) {
            $light = $lights->first();
            $lights->forAll(function ($key, $light) {
                /** @var \IKEA\Tradfri\Device\Lightbulb $light */
                echo '---------- Light Information'.PHP_EOL;
                echo '- ID: ' . $light->getId(). PHP_EOL;
                echo '- Name: ' . $light->getName(). PHP_EOL;
                echo '- Manufacturer: ' . $light->getManufacturer(). PHP_EOL;
                echo '- Version: ' . $light->getVersion(). PHP_EOL;
                echo '- State is: ' . $light->getState(). PHP_EOL;
                echo '- Brightness ' . $light->getBrightness().'%'. PHP_EOL;
                echo ' '.PHP_EOL;

                return true;
            });
        }
        echo '---------- Switch Group'.PHP_EOL;
        echo 'group is: ' . ($group->isOn() ? 'on' : 'off') . PHP_EOL;
        echo 'brightness is: ' . $group->getBrightness() . PHP_EOL;
        $group->isOn() ? null : $group->on();
        if ($group->isOn()) {
            if ($group->dim(15)) {
                echo 'dim to 15'. PHP_EOL;
            }
            sleep(3);
            if ($group->dim(85)) {
                echo 'dim to 85'. PHP_EOL;
            }
            sleep(3);
            if ($group->dim(15)) {
                echo 'dim to 15'. PHP_EOL;
            }
        }
        return true;
    }
} catch (\Exception $e) {
    echo PHP_EOL.'---------- Error';
    echo PHP_EOL. $e->getMessage().PHP_EOL.PHP_EOL;
    print_r($e->getTraceAsString());
    die();
}
