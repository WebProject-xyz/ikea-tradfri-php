<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Command;

class Put extends AbstractCommand
{
    public const COAP_COMMAND = 'coap-client -m put -u "%s" -k "%s"';
}
