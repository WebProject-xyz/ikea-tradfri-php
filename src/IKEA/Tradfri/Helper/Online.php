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
     * @param string $ip
     *
     * @return bool
     */
    public static function isOnline(string $ip): bool
    {
        try {
            $re = '/(\ \d+% packet loss), (time \d+ms)/';
            $data = Runner::execWithTimeout('ping -c1 '.$ip, 1, false);

            preg_match_all($re, $data, $matches, PREG_SET_ORDER);
            if (isset($matches[0][1])) {
                if (\strpos($matches[0][1], ' 0% packet loss') === false) {
                    throw new RuntimeException('packet loss detected');
                }

                if (\strpos($matches[0][2], 'time 0ms') === false) {
                    throw new RuntimeException('ping to high');
                }

                return true;
            }

            throw new RuntimeException('unable to ping hub');
        } catch (\Exception $e) {
            // todo add log
            return false;
        }
    }
}
