<?php

namespace App\Model;

/**
 *
 */
class AdminBicycleManager extends AbstractManager
{
    public const TABLE = 'bike';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function insert(array $bike): void
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE .
        "(name, weight,stock, category_id, image, maximum_charge, autonomy, frame_size, created_date) 
        VALUES (:name, :weight,:stock, :category_id, :image, :maximum_charge, :autonomy,:frame_size, NOW())");
        $statement->bindValue('name', $bike['name'], \PDO::PARAM_STR);
        $statement->bindValue('weight', $bike['weight']);
        $statement->bindValue('stock', $bike['stock'], \PDO::PARAM_INT);
        $statement->bindValue('category_id', $bike['category_id'], \PDO::PARAM_INT);
        $statement->bindValue('image', $bike['image'], \PDO::PARAM_STR);
        $statement->bindValue('maximum_charge', $bike['maximum_charge'], \PDO::PARAM_INT);
        $statement->bindValue('autonomy', $bike['autonomy'], \PDO::PARAM_STR);
        $statement->bindValue('frame_size', $bike['frame_size'], \PDO::PARAM_STR);

        $statement->execute();
    }
}
