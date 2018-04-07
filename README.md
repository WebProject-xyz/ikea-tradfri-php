# ikea-tradfri-api
php api to control Ikea smart lights (tradfri)

## requirements
#### coap with dTLS

* https://github.com/hvanderlaan/ikea-smartlight

### Status 

#### Stable:
[![Build Status](https://travis-ci.org/WebProject-xyz/ikea-tradfri-php.svg?branch=master)](https://travis-ci.org/WebProject-xyz/ikea-tradfri-php)
[![Code Climate](https://img.shields.io/codeclimate/github/WebProject-xyz/ikea-tradfri-php.svg?style=flat-square)](https://codeclimate.com/github/WebProject-xyz/ikea-tradfri-php/)
[![Code Climate](https://img.shields.io/codeclimate/issues//github/WebProject-xyz/ikea-tradfri-php.svg?style=flat-square)](https://codeclimate.com/github/WebProject-xyz/ikea-tradfri-php/)
[![Maintainability](https://api.codeclimate.com/v1/badges/c3a38c872794aa6a83c9/maintainability)](https://codeclimate.com/github/WebProject-xyz/ikea-tradfri-php/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/c3a38c872794aa6a83c9/test_coverage)](https://codeclimate.com/github/WebProject-xyz/ikea-tradfri-php/test_coverage)
[![codecov](https://codecov.io/gh/WebProject-xyz/ikea-tradfri-php/branch/master/graph/badge.svg)](https://codecov.io/gh/WebProject-xyz/ikea-tradfri-php)
[![StyleCI](https://styleci.io/repos/115823629/shield?branch=master)](https://styleci.io/repos/115823629)

#### Develop:
[![Build Status](https://travis-ci.org/WebProject-xyz/ikea-tradfri-php.svg?branch=develop)](https://travis-ci.org/WebProject-xyz/ikea-tradfri-php)
[![codecov](https://codecov.io/gh/WebProject-xyz/ikea-tradfri-php/branch/develop/graph/badge.svg)](https://codecov.io/gh/WebProject-xyz/ikea-tradfri-php)
[![StyleCI](https://styleci.io/repos/115823629/shield?branch=develop)](https://styleci.io/repos/115823629)

## Example usage:

see `wiki/example` folder


##### ToDo

- Single command classes
- Executor for commands
- Json data response
- PSR7 ?

## Docker Coap-Client [WIP]
work in progress
`docker build . -t webproject/coap-client:latest`

# Run command [WIP]
 work in progress
`docker run --rm --name coap-client2 webproject/coap-client` 
