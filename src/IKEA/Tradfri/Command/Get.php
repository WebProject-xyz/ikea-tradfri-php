<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Command;

class Get extends AbstractCommand
{
    public const COAP_COMMAND = 'coap-client -m get -u "%s" -k "%s"';
}
