<?php
declare(strict_types=1);

namespace IKEA\Tradfri\Command;

/**
 * Class CommandAbstract
 *
 * @package IKEA\Tradfri\Command
 */
abstract class CommandAbstract
{
    const COAP_COMMAND_GET = 'coap-client -m get -u "%s" -k "%s"';
    const COAP_COMMAND_PUT = 'coap-client -m put -u "%s" -k "%s"';
    const COAP_COMMAND_POST = 'coap-client -m post -u "%s" -k "%s"';

    protected $_client;

    abstract public function __toString();
}
