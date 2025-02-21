<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Benjamin Fahl
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/WebProject-xyz/ikea-tradfri-php
 */

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

use IKEA\Tradfri\Dto\CoapGatewayAuthConfigDto;

$api = (new IKEA\Tradfri\Factory\GatewayServiceFactory(
    new CoapGatewayAuthConfigDto(
        COAP_API_USER,
        COAP_API_KEY,
        COAP_GATEWAY_IP,
        COAP_GATEWAY_SECRET,
    ),
))();
