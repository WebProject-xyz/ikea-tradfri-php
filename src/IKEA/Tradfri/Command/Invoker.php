<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Command;

/**
 * Class Invoker.
 */
class Invoker
{
    /**
     * @var CommandInterface
     */
    protected $_command;

    /**
     * in the invoker we find this kind of method for subscribing the command
     * There can be also a stack, a list, a fixed set ...
     */
    public function setCommand(CommandInterface $cmd) : void
    {
        $this->_command = $cmd;
    }

    /**
     * executes the command; the invoker is the same whatever is the command.
     */
    public function run() : void
    {
        $this->_command->execute();
    }
}
