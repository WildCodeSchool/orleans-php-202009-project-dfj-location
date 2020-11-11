<?php

namespace App\Model;

use App\Controller\BicycleController;

class ReservationManager extends AbstractManager
{
    public const TABLE = 'reservation';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function selectNotValidated(): array
    {
        return $this->pdo->query(
            'SELECT r.id, DATE_FORMAT(r.withdrawal_date, "%d/%m/%Y") as withdrawal, r.number_bike, r.is_validated,' .
            ' b.name as bicycle, b.stock as stock, c.name as category' .
            ' FROM ' . self::TABLE . ' r' .
            ' JOIN ' . BicycleManager::TABLE . ' b ON r.bike_id = b.id' .
            ' JOIN ' . CategoryManager::TABLE . ' c ON b.category_id = c.id' .
            ' ORDER BY withdrawal'
        )->fetchAll();
    }

    public function selectOneById(int $id)
    {
        $statement = $this->pdo->prepare(
            'SELECT r.*, DATE_FORMAT(r.withdrawal_date, "%d/%m/%Y") as withdrawal,' .
            ' b.name as bicycle, b.stock as stock, c.name as category' .
            ' FROM ' . self::TABLE . ' r' .
            ' JOIN ' . BicycleManager::TABLE . ' b ON r.bike_id = b.id' .
            ' JOIN ' . CategoryManager::TABLE . ' c ON b.category_id = c.id' .
            ' WHERE r.id=:id'
        );
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

    public function acceptReservation(int $id)
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET is_validated = 'OK' WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);

        return $statement->execute();
    }

    public function refuseReservation($id)
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET is_validated = 'KO' WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);

        return $statement->execute();
    }
}
