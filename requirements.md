# ikea-tradfri-api

> php api to control Ikea smart lights (tradfri)

## requirements

### coap with dTLS

at this moment there is no coap libs with dTLS, the ikea smart lights are using dTLS with coap for security. the only
option is to build a new libcoap with dTLS included. libcoap requires cunit, a2x, doxygen and dot you need to install
these requirements first.

```bash
sudo apt-get install automake libtool git clone --depth 1 --recursive -b
dtls https://github.com/home-assistant/libcoap.git
cd libcoap ./autogen.sh ./configure --disable-documentation --disable-shared --without-debug CFLAGS="-D
COAP_DEBUG_FD=stderr"
make sudo make install
```

### libcoap usage

```bash
# as of gateway version 1.1.15 the usage of securityid is prohibated, you need to register a api user and you will get a pre shared key from the gateway. follow the steps below and all should be well

coap-client -m post -u "Client_identity" -k "SECURITY_CODE" -e '{"9090":"php-api-user"}' "coaps://IP_ADDRESS:5684/15011/9063"
# SECURITY_CODE = the security code under the gateway
# IDENTITY     = your api user
coap-client -m get -u "php-api-user" -k "PRE SHARED KEY" "coaps://IP_ADDRESS:5684/15011/15012" 2> /dev/null
# Apple HomeKit code looks like: { ... 9083: XXX-XX-XXX, ...}
# XXX-XX-XXX is your HomeKit code

# getting tradfri pre shared key
coap-client -m post -u "Client_identity" -k "<key>" -e '{"9090":"php-api-user"}' "coaps://<hub>:5684/15011/9063"
# getting tradfri information
coap-client -m get -u "php-api-user" -k "<psk>" "coaps://<hup>:5684/15001"
# getting tradfri lightBulb status
coap-client -m get -u "php-api-user" -k "<psk>" "coaps://<hup>:5684/15001/65537"

# turn on tradfri lightBulb
coap-client -m put -u "php-api-user" -k "<psk>" -e '{ "3311" : [{ "5850" : 1 ]} }' "coaps://<hup>:5684/15001/65537"
# turn off tradfri lightBulb
coap-client -m put -u "php-api-user" -k "<psk>" -e '{ "3311" : [{ "5850" : 0 ]} }' "coaps://<hup>:5684/15001/65537"
```
