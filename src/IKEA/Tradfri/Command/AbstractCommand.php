<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Command;

use IKEA\Tradfri\Command\Coap\Receiver;

/**
 * Class AbstractCommand.
 */
abstract class AbstractCommand implements CommandInterface
{
    /**
     * @var Receiver
     */
    protected $_receiver;

    /**
     * AbstractCommand constructor.
     */
    public function __construct(Receiver $_receiver)
    {
        $this->_receiver = $_receiver;
    }

    /**
     * Build command from coap command.
     */
    abstract protected function _buildCommand(): string;

    public function __toString(): string
    {
        return $this->_buildCommand();
    }

    /**
     * this is the most important method in the Command pattern,
     * The Receiver goes in the constructor.
     */
    public function execute()
    {
        // @TODO: Implement execute() method.
    }
}
