<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Helper;

/**
 * Class Keys.
 */
class CoapCommandKeys
{
    const KEY_MANUFACTURER = '0';
    const KEY_TYPE         = '1';
    const KEY_DATA         = '3';
    const KEY_VERSION      = '3';

    const KEY_DEVICE_DATA = '3311';

    const KEY_COLOR   = '5709';
    const KEY_COLOR_2 = '5710';
    const KEY_ONOFF   = '5850';
    const KEY_DIMMER  = '5851';

    const KEY_NAME        = '9001';
    const KEY_TIME        = '9002';
    const KEY_ID          = '9003';
    const KEY_GROUPS_DATA = '9018';
    const KEY_X           = '9039';
    const KEY_SHARED_KEY  = '9091';

    const KEY_GET_DATA       = '15001';
    const KEY_GET_LIGHTS     = '15002';
    const KEY_GET_GROUPS     = '15004';
    const KEY_GET_SHARED_KEY = '15011/9063';
}
