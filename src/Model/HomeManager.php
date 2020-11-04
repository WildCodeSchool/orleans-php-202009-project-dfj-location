<?php

/**
 * Created by PhpStorm.
 * User: sylvain
 * Date: 07/03/18
 * Time: 18:20
 * PHP version 7
 */

namespace App\Model;

/**
 *
 */
class HomeManager extends AbstractManager
{
    /**
     *
     */
    public const TABLE = 'bike';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function selectNewestBikes()
    {
        return $this->pdo->query('SELECT name, image FROM ' . self::TABLE .
            ' ORDER BY created_date DESC LIMIT 4 ')->fetchAll();
    }
}
