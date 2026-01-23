<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025-2026 Benjamin Fahl
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/WebProject-xyz/ikea-tradfri-php
 */

namespace IKEA\Tradfri\Command\Coap;

/**
 * @todo: enum
 *
 * @see Source and Thanks to "ggravlingen"
 * https://github.com/ggravlingen/pytradfri/blob/master/pytradfri/const.py
 */
final class Keys
{
    final public const string ROOT_DEVICES                                = '15001';
    final public const string ROOT_GATEWAY                                = '15011';
    final public const string ROOT_GROUPS                                 = '15004';
    final public const string ROOT_MOODS                                  = '15005';
    final public const string ROOT_NOTIFICATION                           = '15006';  // speculative name
    final public const string ROOT_REMOTE_CONTROL                         = '15009';
    final public const string ROOT_SIGNAL_REPEATER                        = '15014';
    final public const string ROOT_SMART_TASKS                            = '15010';
    final public const string ROOT_START_ACTION                           = '15013';  // found under ATTR_START_ACTION
    final public const string ATTR_START_BLINDS                           = '15015';
    final public const string ROOT_AIR_PURIFIER                           = '15025';
    final public const string ATTR_ALEXA_PAIR_STATUS                      = '9093';
    final public const string ATTR_AUTH                                   = '9063';
    final public const string ATTR_APPLICATION_TYPE                       = '5750';
    final public const int ATTR_APPLICATION_TYPE_BLIND                    = 7;
    final public const string ATTR_BLIND_CURRENT_POSITION                 = '5536';
    final public const string ATTR_BLIND_TRIGGER                          = '5523';
    final public const string ATTR_AIR_PURIFIER_MODE                      = '5900';
    final public const string ATTR_AIR_PURIFIER_FILTER_RUNTIME            = '5902';
    final public const string ATTR_AIR_PURIFIER_FILTER_STATUS             = '5903';
    final public const string ATTR_AIR_PURIFIER_FILTER_LIFETIME_TOTAL     = '5904';
    final public const string ATTR_AIR_PURIFIER_CONTROLS_LOCKED           = '5905';
    final public const string ATTR_AIR_PURIFIER_LEDS_OFF                  = '5906';
    final public const string ATTR_AIR_PURIFIER_AIR_QUALITY               = '5907';
    final public const string ATTR_AIR_PURIFIER_FAN_SPEED                 = '5908';
    final public const string ATTR_AIR_PURIFIER_MOTOR_RUNTIME_TOTAL       = '5909';
    final public const string ATTR_AIR_PURIFIER_FILTER_LIFETIME_REMAINING = '5910';
    final public const int ATTR_AIR_PURIFIER_MODE_AUTO                    = 1;
    final public const string ATTR_CERTIFICATE_PEM                        = '9096';
    final public const string ATTR_CERTIFICATE_PROV                       = '9092';
    final public const string ATTR_CLIENT_IDENTITY_PROPOSED               = '9090';
    final public const string ATTR_CREATED_AT                             = '9002';
    final public const string ATTR_COGNITO_ID                             = '9101';
    final public const string ATTR_COMMISSIONING_MODE                     = '9061';
    final public const string ATTR_CURRENT_TIME_UNIX                      = '9059';
    final public const string ATTR_CURRENT_TIME_ISO8601                   = '9060';
    final public const string ATTR_DEVICE_INFO                            = '3';
    final public const string ATTR_GATEWAY_ID_2                           = '9100';  // stored in IKEA app code as gateway id
    final public const string ATTR_GATEWAY_TIME_SOURCE                    = '9071';
    final public const string ATTR_GATEWAY_UPDATE_PROGRESS                = '9055';
    final public const string ATTR_GROUP_MEMBERS                          = '9018';
    final public const string ATTR_GROUP_ID                               = '9038';
    final public const string ATTR_HOMEKIT_ID                             = '9083';
    final public const string ATTR_HS_LINK                                = '15002';
    final public const string ATTR_ID                                     = '9003';
    final public const string ATTR_IDENTITY                               = '9090';
    final public const string ATTR_IOT_ENDPOINT                           = '9103';
    final public const string ATTR_KEY_PAIR                               = '9097';
    final public const string ATTR_LAST_SEEN                              = '9020';
    final public const string ATTR_LIGHT_CONTROL                          = '3311';  // array
    final public const string ATTR_MASTER_TOKEN_TAG                       = '9036';
    final public const string ATTR_MOOD                                   = '9039';
    final public const string ATTR_NAME                                   = '9001';
    final public const string ATTR_NTP                                    = '9023';
    final public const string ATTR_FIRMWARE_VERSION                       = '9029';
    final public const string ATTR_FIRST_SETUP                            = '9069';  // ??? unix epoch value when gateway first setup
    final public const string ATTR_GATEWAY_INFO                           = '15012';
    final public const string ATTR_GATEWAY_ID                             = '9081';  // ??? id of the gateway
    final public const string ATTR_GATEWAY_REBOOT                         = '9030';  // gw reboot
    final public const string ATTR_GATEWAY_FACTORY_DEFAULTS               = '9031';  // gw to factory defaults
    final public const string ATTR_GATEWAY_FACTORY_DEFAULTS_MIN_MAX_MSR   = '5605';
    final public const string ATTR_GOOGLE_HOME_PAIR_STATUS                = '9105';
    final public const string ATTR_DEVICE_STATE                           = '5850';  // 0 / 1
    final public const string ATTR_LIGHT_DIMMER                           = '5851';  // Dimmer, not following spec: 0..255
    final public const string ATTR_LIGHT_COLOR_HEX                        = '5706';  // string representing a value in hex
    final public const string ATTR_LIGHT_COLOR_X                          = '5709';
    final public const string ATTR_LIGHT_COLOR_Y                          = '5710';
    final public const string ATTR_LIGHT_COLOR_HUE                        = '5707';
    final public const string ATTR_LIGHT_COLOR_SATURATION                 = '5708';
    final public const string ATTR_LIGHT_MIREDS                           = '5711';
    final public const string ATTR_NOTIFICATION_EVENT                     = '9015';
    final public const string ATTR_NOTIFICATION_NVPAIR                    = '9017';
    final public const string ATTR_NOTIFICATION_STATE                     = '9014';
    final public const string ATTR_OTA_TYPE                               = '9066';
    final public const string ATTR_OTA_UPDATE_STATE                       = '9054';
    final public const string ATTR_OTA_UPDATE                             = '9037';
    final public const string ATTR_PUBLIC_KEY                             = '9098';
    final public const string ATTR_PRIVATE_KEY                            = '9099';
    final public const string ATTR_PSK                                    = '9091';
    final public const string ATTR_REACHABLE_STATE                        = '9019';
    final public const string ATTR_REPEAT_DAYS                            = '9041';
    final public const string ATTR_SEND_CERT_TO_GATEWAY                   = '9094';
    final public const string ATTR_SEND_COGNITO_ID_TO_GATEWAY             = '9095';
    final public const string ATTR_SEND_GH_COGNITO_ID_TO_GATEWAY          = '9104';
    final public const string ATTR_SENSOR                                 = '3300';
    final public const string ATTR_SENSOR_MAX_RANGE_VALUE                 = '5604';
    final public const string ATTR_SENSOR_MAX_MEASURED_VALUE              = '5602';
    final public const string ATTR_SENSOR_MIN_RANGE_VALUE                 = '5603';
    final public const string ATTR_SENSOR_MIN_MEASURED_VALUE              = '5601';
    final public const string ATTR_SENSOR_TYPE                            = '5751';
    final public const string ATTR_SENSOR_UNIT                            = '5701';
    final public const string ATTR_SENSOR_VALUE                           = '5700';
    final public const string ATTR_START_ACTION                           = '9042';  // array
    final public const array ATTR_SMART_TASK_TYPE                         = [
        '9040',  // 4 = transition | 1 = not home | 2 = on/off
    ];
    final public const int ATTR_SMART_TASK_NOT_AT_HOME                = 1;
    final public const int ATTR_SMART_TASK_LIGHTS_OFF                 = 2;
    final public const int ATTR_SMART_TASK_WAKE_UP                    = 4;
    final public const string ATTR_SMART_TASK_TRIGGER_TIME_INTERVAL   = '9044';
    final public const string ATTR_SMART_TASK_TRIGGER_TIME_START_HOUR = '9046';
    final public const string ATTR_SMART_TASK_TRIGGER_TIME_START_MIN  = '9047';
    final public const string ATTR_SWITCH_CUM_ACTIVE_POWER            = '5805';
    final public const string ATTR_SWITCH_ON_TIME                     = '5852';
    final public const string ATTR_SWITCH_PLUG                        = '3312';
    final public const string ATTR_SWITCH_POWER_FACTOR                = '5820';
    final public const string ATTR_TIME_END_TIME_HOUR                 = '9048';
    final public const string ATTR_TIME_END_TIME_MINUTE               = '9049';
    final public const string ATTR_TIME_START_TIME_HOUR               = '9046';
    final public const string ATTR_TIME_START_TIME_MINUTE             = '9047';
    final public const string ATTR_TRANSITION_TIME                    = '5712';
    final public const string ATTR_USE_CURRENT_LIGHT_SETTINGS         = '9070';

    // URL to json-file containing links to all firmware updates
    final public const string URL_OTA_FW = 'https://fw.ota.homesmart.ikea.net/feed/version_info.json';

    // Mireds range that white-spectrum bulbs can show
    final public const array RANGE_MIREDS = ['min' => 250, 'max' => 454];

    // Hue of a RGB bulb
    final public const array RANGE_HUE = ['min' => 0, 'max' => 65535];

    // Effective saturation range of a RGB bulb. The bulb will accept
    // slightly higher values, but it won't produce any light.
    final public const array RANGE_SATURATION = ['min' => 0, 'max' => 65279];

    // Brightness range of all bulbs. 0 will turn off the lamp
    final public const array RANGE_BRIGHTNESS = ['min' => 0, 'max' => 254];

    // XY color
    final public const array RANGE_X = ['min' => 0, 'max' => 65535];
    final public const array RANGE_Y = ['min' => 0, 'max' => 65535];

    // Support info
    final public const int SUPPORT_BRIGHTNESS              = 1;
    final public const int SUPPORT_COLOR_TEMP              = 2;
    final public const int SUPPORT_HEX_COLOR               = 4;
    final public const int SUPPORT_RGB_COLOR               = 8;
    final public const int SUPPORT_XY_COLOR                = 16;
    final public const string ATTR_DEVICE_MANUFACTURER     = '0';
    final public const string ATTR_DEVICE_MODEL_NUMBER     = '1';
    final public const string ATTR_DEVICE_SERIAL           = '2';
    final public const string ATTR_DEVICE_FIRMWARE_VERSION = '3';
    final public const string ATTR_DEVICE_POWER_SOURCE     = '6';
    final public const string ATTR_DEVICE_BATTERY          = '9';

    // end github list

    final public const string ATTR_GROUP_LIGHTS             = '15002';

    // @todo: add more device types and move types to config
    final public const string ATTR_DEVICE_INFO_TYPE_MOTION_SENSOR                  = 'TRADFRI motion sensor';
    final public const string ATTR_DEVICE_INFO_TYPE_CONTROL_OUTLET                 = 'TRADFRI control outlet';
    final public const string ATTR_DEVICE_INFO_TYPE_REMOTE_CONTROL                 = 'TRADFRI remote control';
    final public const string ATTR_DEVICE_INFO_TYPE_DIMMER                         = 'TRADFRI dimmer';
    final public const string ATTR_DEVICE_INFO_TYPE_DRIVER_10W                     = 'TRADFRI Driver 10W';
    final public const string ATTR_DEVICE_INFO_TYPE_DRIVER_30W                     = 'TRADFRI Driver 30W';
    final public const string ATTR_DEVICE_INFO_TYPE_BLUB                           = 'TRADFRI bulb';
    final public const string ATTR_DEVICE_INFO_TYPE_BLUB_E27_WS                    = 'TRADFRI bulb E27 WS opal 980lm';
    final public const string ATTR_DEVICE_INFO_TYPE_BLUB_E27_W                     = 'TRADFRI bulb E27 W opal 1000lm';
    final public const string ATTR_DEVICE_INFO_TYPE_BLUB_E27_CWS_PAL_600_LM        = 'TRADFRI bulb E27 CWS opal 600lm';
    final public const string ATTR_DEVICE_INFO_TYPE_BLUB_GU10_WS                   = 'TRADFRI bulb GU10 WS 400lm';
    final public const string ATTR_DEVICE_INFO_TYPE_BLUB_GU10_W                    = 'TRADFRI bulb GU10 W 400lm';
    final public const string ATTR_DEVICE_INFO_TYPE_BLUB_E14_W                     = 'TRADFRI bulb E14 W op/ch 400lm';
    final public const string ATTR_DEVICE_INFO_TYPE_REPEATER                       = 'TRADFRI Signal Repeater';
    final public const string ATTR_DEVICE_INFO_TYPE_FLOALT_30X90                   = 'FLOALT panel WS 30x90';
    final public const string ATTR_DEVICE_INFO_TYPE_FLOALT_60X60                   = 'FLOALT panel WS 60x60';
    final public const string ATTR_DEVICE_INFO_TYPE_FLOALT_30X30                   = 'FLOALT panel WS 30x30';
    final public const string ATTR_DEVICE_INFO_TYPE_OPEN_CLOSE_REMOTE              = 'TRADFRI open/close remote';
    final public const string ATTR_DEVICE_INFO_TYPE_ROLLER_BLIND                   = 'FYRTUR block-out roller blind';
}
