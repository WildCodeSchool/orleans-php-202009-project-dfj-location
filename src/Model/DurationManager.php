<?php

namespace App\Model;

class DurationManager extends AbstractManager
{
    public const TABLE = 'duration';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }
}
