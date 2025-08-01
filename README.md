# ikea-tradfri-api

> PHP api to control Ikea smart lights (tradfri)

-----

Inspired by

> [hvanderlaan/ikea-smartlight](https://github.com/hvanderlaan/ikea-smartlight) _Python_

## Bages

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/a04395190abd4cb1aa4fa9c7c8077810)](https://www.codacy.com/gh/WebProject-xyz/ikea-tradfri-php/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=WebProject-xyz/ikea-tradfri-php&amp;utm_campaign=Badge_Grade)
[![QA](https://github.com/WebProject-xyz/ikea-tradfri-php/actions/workflows/grump.yml/badge.svg)](https://github.com/WebProject-xyz/ikea-tradfri-php/actions/workflows/grump.yml)
[![Codacy Badge](https://app.codacy.com/project/badge/Coverage/a04395190abd4cb1aa4fa9c7c8077810)](https://app.codacy.com/gh/WebProject-xyz/ikea-tradfri-php/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_coverage)
[![codecov](https://codecov.io/gh/WebProject-xyz/ikea-tradfri-php/branch/main/graph/badge.svg)](https://codecov.io/gh/WebProject-xyz/ikea-tradfri-php)

### Requirements and initial setup

> see [requirements.md](requirements.md)

## How to use

- [Example config](wiki/example/init-dist.php)
- [wiki/example](wiki/example)

## Docker Coap-Client

### Get client

> <https://hub.docker.com/r/webproject/coap-client>

```bash
docker pull webproject/coap-client:latest
```

### Run command in Docker

> Generate API User and <COAP_API_KEY> (Shared Key)

```bash
docker run --rm --name coap-client webproject/coap-client -m post -u "Client_identity" -k "<COAP_GATEWAY_SECRET>" -e '{"9090":"php-api-user"}' "coaps://<COAP_GATEWAY_IP>:5684/15011/9063""
```

### Get all api endpoints

```bash
docker run --rm --name coap-client webproject/coap-client -m get -u "php-api-user"  -k "<COAP_API_KEY>" "coaps://<COAP_GATEWAY_IP>:5684/.well-known/core"
``` 
