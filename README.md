# ikea-tradfri-api
php api to control Ikea smart lights (tradfri)

## requirements
#### coap with dTLS

* https://github.com/hvanderlaan/ikea-smartlight

### Status 

#### Stable:
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/b317b3e9521740e59e7dff003a0cbd69)](https://app.codacy.com/app/Fahl-Design/ikea-tradfri-php?utm_source=github.com&utm_medium=referral&utm_content=WebProject-xyz/ikea-tradfri-php&utm_campaign=badger)
[![Build Status](https://travis-ci.org/WebProject-xyz/ikea-tradfri-php.svg?branch=master)](https://travis-ci.org/WebProject-xyz/ikea-tradfri-php)
[![Code Climate](https://img.shields.io/codeclimate/github/WebProject-xyz/ikea-tradfri-php.svg?style=flat-square)](https://codeclimate.com/github/WebProject-xyz/ikea-tradfri-php/)
[![Code Climate](https://img.shields.io/codeclimate/issues//github/WebProject-xyz/ikea-tradfri-php.svg?style=flat-square)](https://codeclimate.com/github/WebProject-xyz/ikea-tradfri-php/)
[![Maintainability](https://api.codeclimate.com/v1/badges/c3a38c872794aa6a83c9/maintainability)](https://codeclimate.com/github/WebProject-xyz/ikea-tradfri-php/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/c3a38c872794aa6a83c9/test_coverage)](https://codeclimate.com/github/WebProject-xyz/ikea-tradfri-php/test_coverage)
[![Codacy Badge](https://api.codacy.com/project/badge/Coverage/4706838bc3c245669b351c0920b96b7a)](https://www.codacy.com/app/Fahl-Design/ikea-tradfri-php?utm_source=github.com&utm_medium=referral&utm_content=WebProject-xyz/ikea-tradfri-php&utm_campaign=Badge_Coverage)
[![codecov](https://codecov.io/gh/WebProject-xyz/ikea-tradfri-php/branch/master/graph/badge.svg)](https://codecov.io/gh/WebProject-xyz/ikea-tradfri-php)
[![StyleCI](https://styleci.io/repos/115823629/shield?branch=master)](https://styleci.io/repos/115823629)

#### Develop:
[![Build Status](https://travis-ci.org/WebProject-xyz/ikea-tradfri-php.svg?branch=develop)](https://travis-ci.org/WebProject-xyz/ikea-tradfri-php)
[![codecov](https://codecov.io/gh/WebProject-xyz/ikea-tradfri-php/branch/develop/graph/badge.svg)](https://codecov.io/gh/WebProject-xyz/ikea-tradfri-php)
[![StyleCI](https://styleci.io/repos/115823629/shield?branch=develop)](https://styleci.io/repos/115823629)

## Requirements and initial setup

see [requirements.md](requirements.md)

## Examples:

### Config
- [Example config](wiki/example/init-dist.php)

### Commands
- [wiki/example](wiki/example)

## Docker Coap-Client
#### Build 
`docker build -t webproject/coap-client:latest . `

### Run command in Docker
#### Generate API User and <COAP_API_KEY> (Shared Key)
`docker run --rm --name coap-client webproject/coap-client -m post -u "Client_identity" -k "<COAP_GATEWAY_SECRET>" -e '{"9090":"php-api-user"}' "coaps://<COAP_GATEWAY_IP>:5684/15011/9063""`

#### Get all api endpoints
`docker run --rm --name coap-client webproject/coap-client -m get -u "php-api-user"  -k "<COAP_API_KEY>" "coaps://<COAP_GATEWAY_IP>:5684/.well-known/core"` 
