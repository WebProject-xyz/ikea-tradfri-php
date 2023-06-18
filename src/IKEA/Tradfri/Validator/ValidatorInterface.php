<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Validator;

interface ValidatorInterface
{
    public function isValid($data): bool;
}
