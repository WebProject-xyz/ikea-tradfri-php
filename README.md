# IKEA Tradfri PHP API

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/a04395190abd4cb1aa4fa9c7c8077810)](https://www.codacy.com/gh/WebProject-xyz/ikea-tradfri-php/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=WebProject-xyz/ikea-tradfri-php&amp;utm_campaign=Badge_Grade)
[![QA](https://github.com/WebProject-xyz/ikea-tradfri-php/actions/workflows/grump.yml/badge.svg)](https://github.com/WebProject-xyz/ikea-tradfri-php/actions/workflows/grump.yml)
[![Codacy Badge](https://app.codacy.com/project/badge/Coverage/a04395190abd4cb1aa4fa9c7c8077810)](https://app.codacy.com/gh/WebProject-xyz/ikea-tradfri-php/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_coverage)
[![codecov](https://codecov.io/gh/WebProject-xyz/ikea-tradfri-php/branch/main/graph/badge.svg)](https://codecov.io/gh/WebProject-xyz/ikea-tradfri-php)
[![Release](https://github.com/WebProject-xyz/ikea-tradfri-php/actions/workflows/release.yml/badge.svg)](https://github.com/WebProject-xyz/ikea-tradfri-php/actions/workflows/release.yml)
[![PHP Version](https://img.shields.io/packagist/php-v/webproject-xyz/ikea-tradfri-php-api)](https://packagist.org/packages/webproject-xyz/ikea-tradfri-php-api)
[![Latest Stable Version](https://img.shields.io/packagist/v/webproject-xyz/ikea-tradfri-php-api)](https://packagist.org/packages/webproject-xyz/ikea-tradfri-php-api)
[![Total Downloads](https://img.shields.io/packagist/dt/webproject-xyz/ikea-tradfri-php-api)](https://packagist.org/packages/webproject-xyz/ikea-tradfri-php-api)
[![License](https://img.shields.io/packagist/l/webproject-xyz/ikea-tradfri-php-api)](https://packagist.org/packages/webproject-xyz/ikea-tradfri-php-api)

A powerful PHP library to control IKEA Tradfri smart lights via the Gateway.

---

## 🚀 Quick Start

### 1. Requirements
- PHP 8.4+
- `coap-client` (available via Docker)

### 2. Generate Security Key
Use the [Docker Coap-Client](https://hub.docker.com/r/webproject/coap-client) to get your credentials:

```bash
docker run --rm webproject/coap-client 
  -m post -u "Client_identity" -k "<GATEWAY_SECRET>" 
  -e '{"9090":"php-api-user"}' 
  "coaps://<GATEWAY_IP>:5684/15011/9063"
```

---

## 💻 Usage

### Initialize API
```php
use IKEA\Tradfri\Dto\CoapGatewayAuthConfigDto;
use IKEA\Tradfri\Factory\GatewayServiceFactory;

$api = (new GatewayServiceFactory(
    new CoapGatewayAuthConfigDto(
        username: 'php-api-user',
        apiKey: 'GENERATED_API_KEY',
        gatewayIp: '192.168.1.10',
        gatewaySecret: 'GATEWAY_SECRET'
    )
))();
```

### Control Devices
```php
// Switch on all lights and dim to 80%
foreach ($api->getLights() as $light) {
    $light->switchOn()->dim(80);
}

// Switch off a specific group
$api->getGroups()->first()?->switchOff();
```

---

## 📖 Documentation
- [Requirements](requirements.md)
- [Examples](wiki/example)

## 🤝 Contributing
Feel free to submit Pull Requests.

## 📄 License
MIT License.
