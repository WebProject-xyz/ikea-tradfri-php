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
     * @param string $ipAddress
     *
     * @return bool
     */
    public static function isOnline(string $ipAddress): bool
    {
        $online = true;
        $regex = '/(\ \d+% packet loss), (time \d+ms)/';

        try {
            $data = Runner::execWithTimeout('ping -c1 '.$ipAddress, 1, false);

            \preg_match_all($regex, $data, $matches, PREG_SET_ORDER);
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
        if (\strpos($matches[0][1], ' 0% packet loss') === false) {
            throw new RuntimeException('packet loss detected');
        }

        if (\strpos($matches[0][2], 'time 0ms') === false) {
            throw new RuntimeException('ping to high');
        }

        return true;
    }
}
