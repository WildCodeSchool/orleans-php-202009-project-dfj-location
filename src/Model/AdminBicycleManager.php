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
            "(name, weight, stock, category_id, image, maximum_charge, autonomy, frame_size, created_date, description) 
        VALUES (:name, :weight, :stock, :category_id, 
        :image, :maximum_charge, :autonomy, :frame_size,NOW(), :description)");
        $statement->bindValue('name', $bike['name'], \PDO::PARAM_STR);
        $statement->bindValue('weight', $bike['weight']);
        $statement->bindValue('stock', $bike['stock'], \PDO::PARAM_INT);
        $statement->bindValue('category_id', $bike['category_id'], \PDO::PARAM_INT);
        $statement->bindValue('image', $bike['image'], \PDO::PARAM_STR);
        $statement->bindValue('maximum_charge', $bike['maximum_charge'], \PDO::PARAM_INT);
        $statement->bindValue('autonomy', $bike['autonomy'], \PDO::PARAM_STR);
        $statement->bindValue('frame_size', $bike['frame_size'], \PDO::PARAM_STR);
        $statement->bindValue('description', $bike['description'], \PDO::PARAM_STR);

        $statement->execute();
    }


    public function update(array $bike, int $id): bool
    {

        // prepared request
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET name = :name, weight = :weight,
        category_id = :category_id, image = :image, maximum_charge = :maximum_charge, autonomy = :autonomy,
        frame_size = :frame_size, stock = :stock, description = :description  WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->bindValue('name', $bike['name'], \PDO::PARAM_STR);
        $statement->bindValue('weight', $bike['weight']);
        $statement->bindValue('category_id', $bike['category_id'], \PDO::PARAM_INT);
        $statement->bindValue('image', $bike['image'], \PDO::PARAM_STR);
        $statement->bindValue('maximum_charge', $bike['maximum_charge'], \PDO::PARAM_INT);
        $statement->bindValue('autonomy', $bike['autonomy'], \PDO::PARAM_STR);
        $statement->bindValue('frame_size', $bike['frame_size'], \PDO::PARAM_STR);
        $statement->bindValue('stock', $bike['stock'], \PDO::PARAM_INT);
        $statement->bindValue('description', $bike['description'], \PDO::PARAM_STR);

        return $statement->execute();
    }
}
