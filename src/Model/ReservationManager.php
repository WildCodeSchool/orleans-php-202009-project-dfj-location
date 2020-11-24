<?php

namespace App\Model;

use App\Controller\BicycleController;

class ReservationManager extends AbstractManager
{
    public const TABLE = 'reservation';
    public const RESERVATION_ACCEPTED = 'Acceptée';
    public const RESERVATION_REFUSED = 'Refusée';

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

    public function selectWithPrice(int $id)
    {
        $statement = $this->pdo->prepare(
            'SELECT r.firstname_visitor, r.lastname_visitor, r.email, (r.number_bike * p.price) as price' .
            ' FROM ' . self::TABLE . ' r' .
            ' JOIN ' . BicycleManager::TABLE . ' b ON r.bike_id = b.id' .
            ' JOIN ' . PriceManager::TABLE . ' p ON b.category_id = p.category_id' .
            ' JOIN ' . DurationManager::TABLE . ' d ON p.duration_id = d.id AND r.duration_id = d.id' .
            ' WHERE r.id=:id'
        );
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
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

    public function acceptReservation(int $id, string $action)
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET is_validated =:action  WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->bindValue('action', $action, \PDO::PARAM_STR);

        return $statement->execute();
    }

    public function insert(array $data): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE .
            " (lastname_visitor,firstname_visitor,phone,email,bike_id,number_bike,withdrawal_date,duration_id,comment) 
        VALUES (:lastname,:firstname,:tel,:email,:bike,:number,:date,:duration,:message)");
        $statement->bindValue('lastname', ucfirst($data['lastname']), \PDO::PARAM_STR);
        $statement->bindValue('firstname', ucfirst($data['firstname']), \PDO::PARAM_STR);
        $statement->bindValue('tel', $data['tel'], \PDO::PARAM_INT);
        $statement->bindValue('email', $data['email'], \PDO::PARAM_STR);
        $statement->bindValue('bike', $data['bike'], \PDO::PARAM_STR);
        $statement->bindValue('number', $data['number'], \PDO::PARAM_INT);
        $statement->bindValue('date', date('Y-m-d', strtotime($data['date'])));
        $statement->bindValue('duration', $data['duration'], \PDO::PARAM_STR);
        $statement->bindValue('message', $data['message'], \PDO::PARAM_STR);

        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
    }

    public function selectAll(): array
    {
        return parent::selectAll();
    }

    /**
     * @param int $id
     * @return bool
     */
    public function isReservedBike(int $id): bool
    {
        $statement = $this->pdo->prepare("SELECT id FROM " . self::TABLE . " WHERE bike_id = :id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);

        $statement->execute();

        return $statement->rowCount() > 0;
    }

    public function numberOfBikeReservation()
    {
        return $this->pdo->query('SELECT ' . BicycleManager::TABLE . '.id AS id, COUNT(reservation.id) 
        AS reservationBike FROM ' . self::TABLE . ' RIGHT JOIN ' . BicycleManager::TABLE . ' ON '
            . BicycleManager::TABLE . '.id=' . self::TABLE . '.bike_id GROUP BY bike.id;')->fetchAll();
    }
}
