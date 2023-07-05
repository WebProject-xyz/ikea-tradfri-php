<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Command;

class Post extends AbstractCommand
{
    final public const COAP_COMMAND = 'coap-client -m post -u "%s" -k "%s"';
}
