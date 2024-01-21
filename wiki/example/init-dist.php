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

use IKEA\Tradfri\Adapter\Coap as Adapter;
use IKEA\Tradfri\Command\GatewayHelperCommands;

if (!\is_file(__DIR__ . '/../../vendor/autoload.php')) {
    exit('run composer up!');
}

require __DIR__ . '/../../vendor/autoload.php';

const COAP_GATEWAY_IP     = 'yourHubIp';
const COAP_GATEWAY_SECRET = 'secretFromBacksideOfHub';

// API Key from: coap-client -m post -u "Client_identity" -k "<COAP_GATEWAY_SECRET>" -e '{"9090":"php-api-user"}' "coaps://<COAP_GATEWAY_IP>/15011/9063"
const COAP_API_KEY  = 'generatedApiKeySeeReadme';
const COAP_API_USER = 'php-api-user';

\defined('COAP_API_KEY') ?: exit('FOLLOW FIRST RUN HELP IN README');
\defined('COAP_GATEWAY_IP') ?: exit('FOLLOW FIRST RUN HELP IN README');
\defined('COAP_GATEWAY_SECRET') ?: exit('FOLLOW FIRST RUN HELP IN README');

// default: no flood protection (time in microseconds)
\defined('COAP_GATEWAY_FLOOD_PROTECTION') ?: \define('COAP_GATEWAY_FLOOD_PROTECTION', 50);

$deviceMapper = new IKEA\Tradfri\Mapper\DeviceData();
$groupMapper  = new IKEA\Tradfri\Mapper\GroupData();
$commands     = new GatewayHelperCommands(
    COAP_GATEWAY_IP,
    COAP_GATEWAY_SECRET,
    COAP_API_KEY,
    COAP_API_USER,
);
$adapter = new Adapter($commands, $deviceMapper, $groupMapper);
$client  = new IKEA\Tradfri\Client\Client($adapter);
$api     = new IKEA\Tradfri\Service\GatewayApiService($client);
