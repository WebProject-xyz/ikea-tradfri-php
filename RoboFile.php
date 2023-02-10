<?php
declare(strict_types=1);

use Robo\Result;

require_once __DIR__ . '/vendor/autoload.php';

/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see https://robo.li/
 */
class RoboFile extends \Robo\Tasks
{
    // define public methods as commands
    public function testUnit(): Result
    {
        return $this->taskCodecept(__DIR__ . '/vendor/bin/codecept')
            ->suite('Unit')
            ->run();
    }
}
