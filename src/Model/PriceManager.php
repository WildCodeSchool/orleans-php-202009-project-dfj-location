<?php

namespace App\Model;

class PriceManager extends AbstractManager
{
    public const TABLE = 'price';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function hasPrice(int $duration, int $category): bool
    {
        $statement = $this->pdo->prepare("SELECT id FROM " . self::TABLE .
            " WHERE duration_id = :duration AND category_id = :category");
        $statement->bindValue('duration', $duration, \PDO::PARAM_INT);
        $statement->bindValue('category', $category, \PDO::PARAM_INT);

        $statement->execute();

        return $statement->rowCount() > 0;
    }
}
