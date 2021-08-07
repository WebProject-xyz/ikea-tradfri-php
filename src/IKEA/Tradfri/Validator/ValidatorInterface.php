<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Validator;

interface ValidatorInterface
{
    /**
     * @param mixed $data
     */
    public function isValid($data): bool;
}
