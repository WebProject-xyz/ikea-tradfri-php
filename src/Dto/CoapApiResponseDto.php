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

namespace IKEA\Tradfri\Dto;

final class CoapApiResponseDto
{
    public const ROOT_DEVICES                               = '15001';
    public const ROOT_GATEWAY                               = '15011';
    public const ROOT_GROUPS                                = '15004';
    public const ROOT_MOODS                                 = '15005';
    public const ROOT_NOTIFICATION                          = '15006';  // speculative name
    public const ROOT_REMOTE_CONTROL                        = '15009';
    public const ROOT_SIGNAL_REPEATER                       = '15014';
    public const ROOT_SMART_TASKS                           = '15010';
    public const ROOT_START_ACTION                          = '15013';  // found under ATTR_START_ACTION
    public const ATTR_START_BLINDS                          = '15015';
    public const ROOT_AIR_PURIFIER                          = '15025';
    public const ATTR_ALEXA_PAIR_STATUS                     = '9093';
    public const ATTR_AUTH                                  = '9063';
    public const ATTR_APPLICATION_TYPE                      = '5750';
    public const ATTR_APPLICATION_TYPE_BLIND                = 7;
    public const ATTR_BLIND_CURRENT_POSITION                = '5536';
    public const ATTR_BLIND_TRIGGER                         = '5523';
    public const ATTR_AIR_PURIFIER_MODE                     = '5900';
    public const ATTR_AIR_PURIFIER_FILTER_RUNTIME           = '5902';
    public const ATTR_AIR_PURIFIER_FILTER_STATUS            = '5903';
    public const ATTR_AIR_PURIFIER_FILTER_LIFETIME_TOTAL    = '5904';
    public const ATTR_AIR_PURIFIER_CONTROLS_LOCKED          = '5905';
    public const ATTR_AIR_PURIFIER_LEDS_OFF                 = '5906';
    public const ATTR_AIR_PURIFIER_AIR_QUALITY              = '5907';
    public const ATTR_AIR_PURIFIER_FAN_SPEED                = '5908';
    public const ATTR_AIR_PURIFIER_MOTOR_RUNTIME_TOTAL      = '5909';
    public const ATTR_AIR_PURIFIER_FILTER_LIFETIME_REMAINING= '5910';
    public const ATTR_AIR_PURIFIER_MODE_AUTO                = 1;
    public const ATTR_CERTIFICATE_PEM                       = '9096';
    public const ATTR_CERTIFICATE_PROV                      = '9092';
    public const ATTR_CLIENT_IDENTITY_PROPOSED              = '9090';
    public const ATTR_CREATED_AT                            = '9002';
    public const ATTR_COGNITO_ID                            = '9101';
    public const ATTR_COMMISSIONING_MODE                    = '9061';
    public const ATTR_CURRENT_TIME_UNIX                     = '9059';
    public const ATTR_CURRENT_TIME_ISO8601                  = '9060';
    public const ATTR_DEVICE_INFO                           = '3';
    public const ATTR_GATEWAY_ID_2                          = '9100';  // stored in IKEA app code as gateway id
    public const ATTR_GATEWAY_TIME_SOURCE                   = '9071';
    public const ATTR_GATEWAY_UPDATE_PROGRESS               = '9055';
    public const ATTR_GROUP_MEMBERS                         = '9018';
    public const ATTR_GROUP_ID                              = '9038';
    public const ATTR_HOMEKIT_ID                            = '9083';
    public const ATTR_HS_LINK                               = '15002';
    public const ATTR_ID                                    = '9003';
    public const ATTR_IDENTITY                              = '9090';
    public const ATTR_IOT_ENDPOINT                          = '9103';
    public const ATTR_KEY_PAIR                              = '9097';
    public const ATTR_LAST_SEEN                             = '9020';
    public const ATTR_LIGHT_CONTROL                         = '3311'; // array
    public const ATTR_MASTER_TOKEN_TAG                      = '9036';
    public const ATTR_MOOD                                  = '9039';
    public const ATTR_NAME                                  = '9001';
    public const ATTR_NTP                                   = '9023';
    public const ATTR_FIRMWARE_VERSION                      = '9029';
    public const ATTR_FIRST_SETUP                           = '9069'; // ??? unix epoch value when gateway first setup
    public const ATTR_GATEWAY_INFO                          = '15012';
    public const ATTR_GATEWAY_ID                            = '9081'; // ??? id of the gateway
    public const ATTR_GATEWAY_REBOOT                        = '9030';  // gw reboot
    public const ATTR_GATEWAY_FACTORY_DEFAULTS              = '9031'; // gw to factory defaults
    public const ATTR_GATEWAY_FACTORY_DEFAULTS_MIN_MAX_MSR  = '5605';
    public const ATTR_GOOGLE_HOME_PAIR_STATUS               = '9105';
    public const ATTR_DEVICE_STATE                          = '5850';  // 0 / 1
    public const ATTR_LIGHT_DIMMER                          = '5851'; // Dimmer, not following spec: 0..255
    public const ATTR_LIGHT_COLOR_HEX                       = '5706';  // string representing a value in hex
    public const ATTR_LIGHT_COLOR_X                         = '5709';
    public const ATTR_LIGHT_COLOR_Y                         = '5710';
    public const ATTR_LIGHT_COLOR_HUE                       = '5707';
    public const ATTR_LIGHT_COLOR_SATURATION                = '5708';
    public const ATTR_LIGHT_MIREDS                          = '5711';
    public const ATTR_NOTIFICATION_EVENT                    = '9015';
    public const ATTR_NOTIFICATION_NVPAIR                   = '9017';
    public const ATTR_NOTIFICATION_STATE                    = '9014';
    public const ATTR_OTA_TYPE                              = '9066';
    public const ATTR_OTA_UPDATE_STATE                      = '9054';
    public const ATTR_OTA_UPDATE                            = '9037';
    public const ATTR_PUBLIC_KEY                            = '9098';
    public const ATTR_PRIVATE_KEY                           = '9099';
    public const ATTR_PSK                                   = '9091';
    public const ATTR_REACHABLE_STATE                       = '9019';
    public const ATTR_REPEAT_DAYS                           = '9041';
    public const ATTR_SEND_CERT_TO_GATEWAY                  = '9094';
    public const ATTR_SEND_COGNITO_ID_TO_GATEWAY            = '9095';
    public const ATTR_SEND_GH_COGNITO_ID_TO_GATEWAY         = '9104';
    public const ATTR_SENSOR                                = '3300';
    public const ATTR_SENSOR_MAX_RANGE_VALUE                = '5604';
    public const ATTR_SENSOR_MAX_MEASURED_VALUE             = '5602';
    public const ATTR_SENSOR_MIN_RANGE_VALUE                = '5603';
    public const ATTR_SENSOR_MIN_MEASURED_VALUE             = '5601';
    public const ATTR_SENSOR_TYPE                           = '5751';
    public const ATTR_SENSOR_UNIT                           = '5701';
    public const ATTR_SENSOR_VALUE                          = '5700';
    public const ATTR_START_ACTION                          = '9042';  // array
    public const ATTR_SMART_TASK_TYPE                       = [
        '9040',  // 4= transition | 1= not home | 2= on/off
    ];
    public const ATTR_SMART_TASK_NOT_AT_HOME            = 1;
    public const ATTR_SMART_TASK_LIGHTS_OFF             = 2;
    public const ATTR_SMART_TASK_WAKE_UP                = 4;
    public const ATTR_SMART_TASK_TRIGGER_TIME_INTERVAL  = '9044';
    public const ATTR_SMART_TASK_TRIGGER_TIME_START_HOUR= '9046';
    public const ATTR_SMART_TASK_TRIGGER_TIME_START_MIN = '9047';
    public const ATTR_SWITCH_CUM_ACTIVE_POWER           = '5805';
    public const ATTR_SWITCH_ON_TIME                    = '5852';
    public const ATTR_SWITCH_PLUG                       = '3312';
    public const ATTR_SWITCH_POWER_FACTOR               = '5820';
    public const ATTR_TIME_END_TIME_HOUR                = '9048';
    public const ATTR_TIME_END_TIME_MINUTE              = '9049';
    public const ATTR_TIME_START_TIME_HOUR              = '9046';
    public const ATTR_TIME_START_TIME_MINUTE            = '9047';
    public const ATTR_TRANSITION_TIME                   = '5712';
    public const ATTR_USE_CURRENT_LIGHT_SETTINGS        = '9070';

    // URL to json-file containing links to all firmware updates
    public const URL_OTA_FW= 'http://fw.ota.homesmart.ikea.net/feed/version_info.json';

    // Mireds range that white-spectrum bulbs can show
    public const RANGE_MIREDS= [250, 454];

    // Hue of a RGB bulb
    public const RANGE_HUE= [0, 65535];

    // Effective saturation range of a RGB bulb. The bulb will accept
    // slightly higher values, but it won't produce any light.
    public const RANGE_SATURATION= [0, 65279];

    // Brightness range of all bulbs. 0 will turn off the lamp
    public const RANGE_BRIGHTNESS  = [0, 254];
    public const RANGE_BLIND       = [0, 100];
    public const RANGE_AIR_PURIFIER= [2, 50];

    // XY color
    public const RANGE_X                     = [0, 65535];
    public const RANGE_Y                     = [0, 65535];
    public const SUPPORT_BRIGHTNESS          = 1;
    public const SUPPORT_COLOR_TEMP          = 2;
    public const SUPPORT_HEX_COLOR           = 4;
    public const SUPPORT_RGB_COLOR           = 8;
    public const SUPPORT_XY_COLOR            = 16;
    public const ATTR_DEVICE_MANUFACTURER    = '0';
    public const ATTR_DEVICE_MODEL_NUMBER    = '1';
    public const ATTR_DEVICE_SERIAL          = '2';
    public const ATTR_DEVICE_FIRMWARE_VERSION= '3';
    public const ATTR_DEVICE_POWER_SOURCE    = '6';
    public const ATTR_DEVICE_BATTERY         = '9';
}
