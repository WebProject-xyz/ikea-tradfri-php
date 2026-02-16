# IKEA Tradfri PHP API

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/a04395190abd4cb1aa4fa9c7c8077810)](https://www.codacy.com/gh/WebProject-xyz/ikea-tradfri-php/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=WebProject-xyz/ikea-tradfri-php&amp;utm_campaign=Badge_Grade)
[![QA](https://github.com/WebProject-xyz/ikea-tradfri-php/actions/workflows/grump.yml/badge.svg)](https://github.com/WebProject-xyz/ikea-tradfri-php/actions/workflows/grump.yml)
[![Codacy Badge](https://app.codacy.com/project/badge/Coverage/a04395190abd4cb1aa4fa9c7c8077810)](https://app.codacy.com/gh/WebProject-xyz/ikea-tradfri-php/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_coverage)
[![codecov](https://codecov.io/gh/WebProject-xyz/ikea-tradfri-php/branch/main/graph/badge.svg)](https://codecov.io/gh/WebProject-xyz/ikea-tradfri-php)
[![Latest Stable Version](https://poser.pugx.org/webproject/ikea-tradfri-php/v/stable)](https://packagist.org/packages/webproject/ikea-tradfri-php)
[![License](https://poser.pugx.org/webproject/ikea-tradfri-php/license)](https://packagist.org/packages/webproject/ikea-tradfri-php)

A powerful and easy-to-use PHP library to control your IKEA Tradfri smart lights through the Gateway.

Inspired by [hvanderlaan/ikea-smartlight](https://github.com/hvanderlaan/ikea-smartlight) (Python).

---

## 🚀 Quick Start

### 1. Requirements

- PHP 8.3 or higher
- `coap-client` (can be run via Docker)
- IKEA Tradfri Gateway

### 2. Initial Setup

To communicate with the gateway, you need to generate a security key. The easiest way is using our [Docker Coap-Client](https://hub.docker.com/r/webproject/coap-client):

```bash
# Pull the client
docker pull webproject/coap-client:latest

# Generate your API User and Shared Key
docker run --rm --name coap-client webproject/coap-client 
  -m post 
  -u "Client_identity" 
  -k "<COAP_GATEWAY_SECRET>" 
  -e '{"9090":"php-api-user"}' 
  "coaps://<COAP_GATEWAY_IP>:5684/15011/9063"
```

---

## 💻 Usage Examples

### Initialize the API

```php
use IKEA\Tradfri\Dto\CoapGatewayAuthConfigDto;
use IKEA\Tradfri\Factory\GatewayServiceFactory;

$config = new CoapGatewayAuthConfigDto(
    username: 'php-api-user',
    apiKey: 'YOUR_GENERATED_API_KEY',
    gatewayIp: '192.168.1.xxx',
    gatewaySecret: 'SECRET_FROM_GATEWAY_BOTTOM'
);

$api = (new GatewayServiceFactory($config))();
```

### Control a Light

```php
// Get all lights
$lights = $api->getLights();

foreach ($lights as $light) {
    echo "Found light: " . $light->getName() . " (" . $light->getReadableState() . ")
";
    
    // Switch on and dim to 80%
    if (!$light->isOn()) {
        $light->switchOn();
    }
    $light->dim(80);
}
```

### Control a Group

```php
$groups = $api->getGroups();

if ($groups->count() > 0) {
    $group = $groups->first();
    echo "Controlling group: " . $group->getName() . "
";
    
    // Switch off the entire group
    $group->switchOff();
}
```

---

## 🛠 Features

- [x] List all devices (Lights, Motion Sensors, Remotes)
- [x] Control individual light bulbs (On/Off, Brightness, Color)
- [x] Manage Groups/Rooms
- [x] Docker support for CoAP communication
- [x] Modern PHP 8.3+ features

## 📖 Documentation

For more detailed information, please refer to:
- [Requirements](requirements.md)
- [Full Example Suite](wiki/example)

---

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📄 License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.
