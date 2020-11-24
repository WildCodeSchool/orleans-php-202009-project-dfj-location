<?php

namespace App\Model;

use mysql_xdevapi\Statement;

class BicycleManager extends AbstractManager
{
    public const TABLE = 'bike';


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

    public function selectByCategory(int $category): array
    {
        // prepared request
        $statement = $this->pdo->prepare(
            'SELECT b.id, b.name, b.image, c.name as model FROM ' . self::TABLE . ' b ' .
            'JOIN category c ON b.category_id=c.id WHERE c.id=:category ' .
            'ORDER BY b.name'
        );
        $statement->bindValue('category', $category, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function selectOneById(int $id)
    {
        $statement = $this->pdo->prepare('SELECT * FROM ' . self::TABLE1 . ' RIGHT JOIN ' . self::TABLE .
            ' ON category.id = category_id WHERE bike.id = :id ');
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function duration(int $id)
    {
        $statement = $this->pdo->prepare('SELECT duration.name, price FROM duration JOIN prices 
        ON prices.duration_id = duration.id JOIN bike ON prices.category_id = bike.category_id  WHERE bike.id = :id 
        ORDER BY duration.id');
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }
}
