<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Helper;

use IKEA\Tradfri\Exception\RuntimeException;

/**
 * Class Online.
 */
class Online
{
    /**
     * Check online state.
     *
     * @param string $gatewayAddress
     *
     * @return bool
     */
    public static function isOnline(string $gatewayAddress): bool
    {
        $online = true;
        $regex = '(\ [\d\.]+% packet loss)';

        try {
            $data = (new Runner())
                ->execWithTimeout('ping -c1 '.$gatewayAddress, 1, false);

            \preg_match_all($regex, $data, $matches, \PREG_SET_ORDER);
            if (isset($matches[0][1])) {
                self::_validateMatches($matches);
            } else {
                throw new RuntimeException('unable to ping hub');
            }
        } catch (\Exception $exception) {
            // todo add log
            $online = false;
        }

        return $online;
    }

    /**
     * Validate matches.
     *
     * @param array $matches
     *
     * @throws \IKEA\Tradfri\Exception\RuntimeException
     *
     * @return bool
     */
    protected static function _validateMatches(array $matches): bool
    {
        if (false === \strpos($matches[0][1], ' 0% packet loss')) {
            throw new RuntimeException('packet loss detected');
        }

        return true;
    }
}
