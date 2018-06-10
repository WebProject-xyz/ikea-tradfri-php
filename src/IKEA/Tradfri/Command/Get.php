<?php
declare(strict_types=1);

namespace IKEA\Tradfri\Command;

/**
 * Class Get.
 */
class Get implements CommandInterface
{
    const COAP_COMMAND = 'coap-client -m get -u "%s" -k "%s"';

    /**
     * Execute command.
     *
     * @return $this
     */
    public function execute(): self
    {
        // @TODO: Implement execute() method.
        return $this;
    }

    /**
     * Echo command.
     *
     * @return string
     */
    public function __toString(): string
    {
        // @TODO: Implement __toString() method.
        return '';
    }
}
