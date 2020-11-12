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

    public function listCategory()
    {
        return $this->pdo->query('SELECT * FROM ' . self::TABLE)->fetchAll();
    }

    public function insert($category)
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (name) VALUES (:name)");
        $statement->bindValue('name', $category['name'], \PDO::PARAM_STR);

        $statement->execute();
    }
}
