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

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function selectByCategory(string $category): array
    {
        // prepared request
        $statement = $this->pdo->prepare(
            'SELECT b.id, b.name, b.image FROM ' . self::TABLE . ' b ' .
            'JOIN category c ON b.category_id=c.id WHERE c.name=:category ' .
            'ORDER BY b.name'
        );
        $statement->bindValue('category', $category, \PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetchAll();
    }
}
