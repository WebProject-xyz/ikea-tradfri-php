<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Command;

/**
 * Class Keys.
 *
 * @see Source and Thanks to "ggravlingen"
 * https://github.com/ggravlingen/pytradfri/blob/master/pytradfri/const.py
 */
class Keys
{
    const ROOT_DEVICES = '15001';
    const ROOT_GATEWAY = '15011';
    const ROOT_GROUPS = '15004';
    const ROOT_MOODS = '15005';
    const ROOT_NOTIFICATION = '15006';  // speculative name
    const ROOT_SMART_TASKS = '15010';
    const ROOT_START_ACTION = '15013';  // found under ATTR_START_ACTION
    const ROOT_SWITCH = '15009';

    const ATTR_ALEXA_PAIR_STATUS = '9093';
    const ATTR_AUTH = '9063';
    const ATTR_APPLICATION_TYPE = '5750';

    const ATTR_CERTIFICATE_PEM = '9096';
    const ATTR_CERTIFICATE_PROV = '9092';
    const ATTR_CLIENT_IDENTITY_PROPOSED = '9090';
    const ATTR_CREATED_AT = '9002';
    const ATTR_COGNITO_ID = '9101';
    const ATTR_COMMISSIONING_MODE = '9061';
    const ATTR_CURRENT_TIME_UNIX = '9059';
    const ATTR_CURRENT_TIME_ISO8601 = '9060';

    const ATTR_DEVICE_INFO = '3';
    const ATTR_DEVICE_INFO_TYPE = '1';

    const ATTR_GATEWAY_TIME_SOURCE = '9071';
    const ATTR_GATEWAY_UPDATE_PROGRESS = '9055';

    const ATTR_HOMEKIT_ID = '9083';
    const ATTR_HS_LINK = '15002';

    const ATTR_ID = '9003';
    const ATTR_IDENTITY = '9090';
    const ATTR_IOT_ENDPOINT = '9103';

    const ATTR_KEY_PAIR = '9097';

    const ATTR_LAST_SEEN = '9020';
    const ATTR_LIGHT_CONTROL = '3311';  // array

    const ATTR_MASTER_TOKEN_TAG = '9036';

    const ATTR_NAME = '9001';
    const ATTR_NTP = '9023';
    const ATTR_FIRMWARE_VERSION = '9029';
    const ATTR_FIRST_SETUP = '9069';  // ??? unix epoch value when gateway first setup

    const ATTR_GATEWAY_INFO = '15012';
    const ATTR_GATEWAY_ID = '9081';  // ??? id of the gateway
    const ATTR_GATEWAY_REBOOT = '9030';  // gw reboot
    const ATTR_GATEWAY_FACTORY_DEFAULTS = '9031';  // gw to factory defaults
    const ATTR_GATEWAY_FACTORY_DEFAULTS_MIN_MAX_MSR = '5605';
    const ATTR_GOOGLE_HOME_PAIR_STATUS = '9105';

    const ATTR_LIGHT_STATE = '5850';  // 0 / 1
    const ATTR_LIGHT_DIMMER = '5851';  // Dimmer, not following spec: 0..255
    const ATTR_LIGHT_COLOR_HEX = '5706';  // string representing a value in hex
    const ATTR_LIGHT_COLOR_X = '5709';
    const ATTR_LIGHT_COLOR_Y = '5710';
    const ATTR_LIGHT_COLOR_HUE = '5707';
    const ATTR_LIGHT_COLOR_SATURATION = '5708';
    const ATTR_LIGHT_MIREDS = '5711';

    const ATTR_NOTIFICATION_EVENT = '9015';
    const ATTR_NOTIFICATION_NVPAIR = '9017';
    const ATTR_NOTIFICATION_STATE = '9014';

    const ATTR_OTA_TYPE = '9066';
    const ATTR_OTA_UPDATE_STATE = '9054';
    const ATTR_OTA_UPDATE = '9037';

    const ATTR_PUBLIC_KEY = '9098';
    const ATTR_PRIVATE_KEY = '9099';
    const ATTR_PSK = '9091';

    const ATTR_REACHABLE_STATE = '9019';
    const ATTR_REPEAT_DAYS = '9041';

    const ATTR_SEND_CERT_TO_GATEWAY = '9094';
    const ATTR_SEND_COGNITO_ID_TO_GATEWAY = '9095';
    const ATTR_SEND_GH_COGNITO_ID_TO_GATEWAY = '9104';
    const ATTR_SENSOR = '3300';
    const ATTR_SENSOR_MAX_RANGE_VALUE = '5604';
    const ATTR_SENSOR_MAX_MEASURED_VALUE = '5602';
    const ATTR_SENSOR_MIN_RANGE_VALUE = '5603';
    const ATTR_SENSOR_MIN_MEASURED_VALUE = '5601';
    const ATTR_SENSOR_TYPE = '5751';
    const ATTR_SENSOR_UNIT = '5701';
    const ATTR_SENSOR_VALUE = '5700';
    const ATTR_START_ACTION = '9042';  // array
    const ATTR_SMART_TASK_TYPE = '9040';  // 4 = transition | 1 = not home | 2 = on/off
    const ATTR_SMART_TASK_NOT_AT_HOME = 1;
    const ATTR_SMART_TASK_LIGHTS_OFF = 2;
    const ATTR_SMART_TASK_WAKE_UP = 4;
    const ATTR_SMART_TASK_TRIGGER_TIME_INTERVAL = '9044';
    const ATTR_SMART_TASK_TRIGGER_TIME_START_HOUR = '9046';
    const ATTR_SMART_TASK_TRIGGER_TIME_START_MIN = '9047';

    const ATTR_SWITCH_CUM_ACTIVE_POWER = '5805';
    const ATTR_SWITCH_ON_TIME = '5852';
    const ATTR_SWITCH_PLUG = '3312';
    const ATTR_SWITCH_POWER_FACTOR = '5820';

    const ATTR_TRANSITION_TIME = '5712';

    const ATTR_USE_CURRENT_LIGHT_SETTINGS = '9070';

    // URL to json-file containing links to all firmware updates
    const URL_OTA_FW = 'https://fw.ota.homesmart.ikea.net/feed/version_info.json';

    // Mireds range that white-spectrum bulbs can show
    const RANGE_MIREDS = ['min' => 250, 'max' => 454];

    // Hue of a RGB bulb
    const RANGE_HUE = ['min' => 0, 'max' => 65535];
    // Effective saturation range of a RGB bulb. The bulb will accept
    // slightly higher values, but it won't produce any light.
    const RANGE_SATURATION = ['min' => 0, 'max' => 65279];
    // Brightness range of all bulbs. 0 will turn off the lamp
    const RANGE_BRIGHTNESS = ['min' => 0, 'max' => 254];

    // XY color
    const RANGE_X = ['min' => 0, 'max' => 65535];
    const RANGE_Y = ['min' => 0, 'max' => 65535];

    // Support info
    const SUPPORT_BRIGHTNESS = 1;
    const SUPPORT_COLOR_TEMP = 2;
    const SUPPORT_HEX_COLOR = 4;
    const SUPPORT_RGB_COLOR = 8;
    const SUPPORT_XY_COLOR = 16;
}
