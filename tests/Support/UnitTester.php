<?php

declare(strict_types=1);

/**
 * Copyright (c) 2024 Benjamin Fahl
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/WebProject-xyz/ikea-tradfri-php
 */

namespace IKEA\Tests\Support;

/**
 * Inherited Methods.
 *
 * @method void am($role)
 * @method void amGoingTo($argumentation)
 * @method void comment($description)
 * @method void execute($callable)
 * @method void expect($prediction)
 * @method void expectTo($prediction)
 * @method void lookForwardTo($achieveValue)
 * @method void pause($vars = [])
 * @method void wantTo($text)
 * @method void wantToTest($text)
 *
 * @SuppressWarnings(PHPMD)
 */
final class UnitTester extends \Codeception\Actor
{
    use _generated\UnitTesterActions;

    /**
     * Define custom actions here.
     */
}
