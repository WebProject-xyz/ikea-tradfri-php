# ikea-tradfri-api

> php api to control Ikea smart lights (tradfri)

## requirements

#### coap with dTLS

* <https://github.com/hvanderlaan/ikea-smartlight>

### Status

#### Stable:

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/b317b3e9521740e59e7dff003a0cbd69)](https://app.codacy.com/app/Fahl-Design/ikea-tradfri-php?utm_source=github.com&utm_medium=referral&utm_content=WebProject-xyz/ikea-tradfri-php&utm_campaign=badger)
[![Cocdeception](https://github.com/WebProject-xyz/ikea-tradfri-php/actions/workflows/cocdeception.yml/badge.svg?branch=master&style=flat)](https://github.com/WebProject-xyz/ikea-tradfri-php/actions/workflows/cocdeception.yml)
[![Maintainability](https://api.codeclimate.com/v1/badges/c3a38c872794aa6a83c9/maintainability)](https://codeclimate.com/github/WebProject-xyz/ikea-tradfri-php/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/c3a38c872794aa6a83c9/test_coverage)](https://codeclimate.com/github/WebProject-xyz/ikea-tradfri-php/test_coverage)
[![codecov](https://codecov.io/gh/WebProject-xyz/ikea-tradfri-php/branch/master/graph/badge.svg)](https://codecov.io/gh/WebProject-xyz/ikea-tradfri-php)
[![StyleCI](https://styleci.io/repos/115823629/shield?branch=master)](https://styleci.io/repos/115823629)

#### Develop:

[![Cocdeception](https://github.com/WebProject-xyz/ikea-tradfri-php/actions/workflows/cocdeception.yml/badge.svg?branch=develop)](https://github.com/WebProject-xyz/ikea-tradfri-php/actions/workflows/cocdeception.yml)
[![Codacy Security Scan](https://github.com/WebProject-xyz/ikea-tradfri-php/actions/workflows/codacy-analysis.yml/badge.svg)](https://github.com/WebProject-xyz/ikea-tradfri-php/actions/workflows/codacy-analysis.yml)
[![codecov](https://codecov.io/gh/WebProject-xyz/ikea-tradfri-php/branch/develop/graph/badge.svg)](https://codecov.io/gh/WebProject-xyz/ikea-tradfri-php)
[![StyleCI](https://styleci.io/repos/115823629/shield?branch=develop)](https://styleci.io/repos/115823629)

## Requirements and initial setup

see [requirements.md](requirements.md)

## Examples:

### Config

- [Example config](wiki/example/init-dist.php)

### Examples

- [wiki/example](wiki/example)

## Docker Coap-Client

#### Get client

`docker pull webproject/coap-client:latest` <https://hub.docker.com/r/webproject/coap-client>

### Run command in Docker

#### Generate API User and <COAP_API_KEY> (Shared Key)

`docker run --rm --name coap-client webproject/coap-client -m post -u "Client_identity" -k "<COAP_GATEWAY_SECRET>" -e '{"9090":"php-api-user"}' "coaps://<COAP_GATEWAY_IP>:5684/15011/9063""`

#### Get all api endpoints

`docker run --rm --name coap-client webproject/coap-client -m get -u "php-api-user"  -k "<COAP_API_KEY>" "coaps://<COAP_GATEWAY_IP>:5684/.well-known/core"` 
