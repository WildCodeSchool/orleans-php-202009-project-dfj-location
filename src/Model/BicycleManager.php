<?php

namespace App\Model;

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
            ' JOIN ' . self::TABLE . ' ON category.id=bike.category_id')->fetchAll();
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

    public function getCategory(int $bicycle)
    {
        $statement = $this->pdo->prepare('SELECT category_id FROM ' . self::TABLE . ' WHERE id=:id');
        $statement->bindValue('id', $bicycle, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }
}
