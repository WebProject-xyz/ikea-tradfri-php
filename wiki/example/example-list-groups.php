<?php
declare(strict_types=1);

use IKEA\Tradfri\Device\Helper\Type;

require __DIR__.'/init.php';

try {
    $lights = $api->getGroups();

    echo '---------- IKEA Tradfri PHP API Example: '.basename(__FILE__).PHP_EOL;
    $lights->forAll(function ($key, $group) {
        /** @var \IKEA\Tradfri\Group\Light $group */
        echo '---------- Group Information'.PHP_EOL;
        echo '- ID: ' . $group->getId(). PHP_EOL;
        echo '- Name: ' . $group->getName(). PHP_EOL;
        echo ' '.PHP_EOL;
        $group->getDevices()->forAll(function ($key, $device) use ($group) {
            /** @var \IKEA\Tradfri\Device\Device|\IKEA\Tradfri\Device\LightBulb $device */
            echo '---------- Device Information in Group: '.$group->getName() .PHP_EOL;
            echo '- ID: ' . $device->getId(). PHP_EOL;
            echo '- Type: ' . $device->getType(). PHP_EOL;
            echo '- Name: ' . $device->getName(). PHP_EOL;
            echo '- GroupName: ' . $group->getName(). PHP_EOL;
            echo '- Manufacturer: ' . $device->getManufacturer(). PHP_EOL;
            echo '- Version: ' . $device->getVersion(). PHP_EOL;
            if ((new Type())->isLightBulb($device->getType())) {
                echo '- State is: ' . $device->getReadableState(). PHP_EOL;
                echo '- Brightness ' . $device->getBrightness().'%'. PHP_EOL;
            }
            echo ' '.PHP_EOL;

            return true;
        });
        return true;
    });
} catch (\Exception $e) {
    echo $e->getMessage().PHP_EOL.PHP_EOL;
    echo $e->getTraceAsString();
    die();
}
