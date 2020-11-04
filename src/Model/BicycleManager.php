<?php

namespace App\Model;

/**
 *
 */
class BicycleManager extends AbstractManager
{
    /**
     *
     */
    protected const TABLE = 'bike';
    protected const TABLE1 = 'category';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
        parent::__construct(self::TABLE1);
    }

    public function selectAllWithCategories()
    {
        return $this->pdo->query('SELECT bike.name, bike.id, category.name as model FROM ' . self::TABLE1 .
            ' JOIN ' . self::TABLE . ' ON category.id=bike.category_id ')->fetchAll();
    }
}
