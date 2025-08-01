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

namespace IKEA\Tradfri\Values;

enum CoapCommandPattern: string
{
    case PUT  = 'coap-client -m put -u "%s" -k "%s"';
    case GET  = 'coap-client -m get -u "%s" -k "%s"';
    case POST = 'coap-client -m post -u "%s" -k "%s"';
}
