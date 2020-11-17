<?php

namespace App\Model;

class DurationManager extends AbstractManager
{
    public const TABLE = 'duration';

    public const ID_HALF_DAY = 1;
    public const HALF_DAY = '1/2 journée';
    public const ID_TWO_WEEKS = 9;
    public const TWO_WEEKS = '2 semaines';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }
}
