<?php
declare(strict_types=1);

namespace IKEA\Tradfri\Traits;

/**
 * Trait ProvidesId
 */
trait ProvidesId
{
    /**
     * @var int
     */
    protected $_id;

    /**
     * Get Id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->_id;
    }

    /**
     * Set Id
     *
     * @param int $id
     *
     * @return static
     */
    public function setId(int $id)
    {
        $this->_id = $id;

        return $this;
    }
}
