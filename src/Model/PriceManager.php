<?php

namespace App\Model;

class PriceManager extends AbstractManager
{
    public const TABLE = 'prices';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }
}
