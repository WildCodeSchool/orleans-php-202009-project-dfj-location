<?php

/**
 * Created by PhpStorm.
 * User: sylvain
 * Date: 07/03/18
 * Time: 20:52
 * PHP version 7
 */

namespace App\Model;

use App\Model\Connection;

/**
 * Abstract class handling default manager.
 */
class CategoryManager extends AbstractManager
{
    /**
     * Initializes Manager Abstract class.
     * @param string $table
     */
    public const TABLE = 'category';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function insert($category)
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (name) VALUES (:name)");
        $statement->bindValue('name', $category['name'], \PDO::PARAM_STR);
        $statement->execute();
    }

    public function update($category)
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET name = :name WHERE id=:id");
        $statement->bindValue('id', $category['id'], \PDO::PARAM_INT);
        $statement->bindValue('name', $category['name'], \PDO::PARAM_STR);
        $statement->execute();
    }

    public function delete(int $id)
    {
        // prepared request
        $statement = $this->pdo->prepare("DELETE FROM " . self::TABLE . " WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }

    public function numberOfBikeInCategory()
    {
        return $this->pdo->query('SELECT ' . self::TABLE . '.id as id, COUNT(bike.id) as nb_bike FROM '
            . BicycleManager::TABLE . ' RIGHT JOIN ' . self::TABLE . ' ON ' . self::TABLE . '.id=category_id GROUP BY '
            . self::TABLE . '.name;')->fetchAll();
    }
}
