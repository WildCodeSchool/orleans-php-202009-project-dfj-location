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

    public function update(array $bike, int $id): bool
    {

        // prepared request
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET name = :name, weight = :weight,
        category_id = :category_id, image = :image, maximum_charge = :maximum_charge, autonomy = :autonomy,
        frame_size = :frame_size, stock = :stock WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->bindValue('name', $bike['name'], \PDO::PARAM_STR);
        $statement->bindValue('weight', $bike['weight']);
        $statement->bindValue('category_id', $bike['category_id'], \PDO::PARAM_INT);
        $statement->bindValue('image', $bike['image'], \PDO::PARAM_STR);
        $statement->bindValue('maximum_charge', $bike['maximum_charge'], \PDO::PARAM_INT);
        $statement->bindValue('autonomy', $bike['autonomy'], \PDO::PARAM_STR);
        $statement->bindValue('frame_size', $bike['frame_size'], \PDO::PARAM_STR);
        $statement->bindValue('stock', $bike['stock'], \PDO::PARAM_INT);

        return $statement->execute();
    }
}
