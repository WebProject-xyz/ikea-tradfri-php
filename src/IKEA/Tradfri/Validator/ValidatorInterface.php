<?php
declare(strict_types=1);

namespace IKEA\Tradfri\Validator;

/**
 * Interface ValidatorInterface.
 */
interface ValidatorInterface
{
    /**
     * Is valid.
     *
     * @param $data
     *
     * @return bool
     */
    public function isValid($data): bool;
}
