<?php

declare(strict_types=1);

namespace IKEA\Tradfri\Traits;

trait ProvidesId
{
    protected int $id;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }
}
