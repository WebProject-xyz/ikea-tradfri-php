#!/usr/bin/env bash

GATEWAY_IP=${GATEWAY_IP-<gateway ip>}
GATEWAY_SECRET=${GATEWAY_SECRET-<gateway secret unter qr code>}

coap-client -m post -u "Client_identity" -k "$GATEWAY_SECRET" -e '{"9090":"php-api-user"}' "coaps://$GATEWAY_IP/15011/9063"
# {"9091":"<yourCoapApiKey>","9029":"1.17.0044"}
