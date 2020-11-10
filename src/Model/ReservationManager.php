<?php

namespace App\Model;

class ReservationManager extends AbstractManager
{
    public const TABLE = 'reservation';


    public function insert(array $data): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE .
            " (lastname_visitor,firstname_visitor,phone,email,bike_id,number_bike,withdrawal_date,comment) 
        VALUES (:lastname,:firstname,:tel,:email,:bike,:number,:date,:message)");
        $statement->bindValue('lastname', ucfirst($data['lastname']), \PDO::PARAM_STR);
        $statement->bindValue('firstname', ucfirst($data['firstname']), \PDO::PARAM_STR);
        $statement->bindValue('tel', $data['tel'], \PDO::PARAM_INT);
        $statement->bindValue('email', $data['email'], \PDO::PARAM_STR);
        $statement->bindValue('bike', $data['bike'], \PDO::PARAM_STR);
        $statement->bindValue('number', $data['number'], \PDO::PARAM_INT);
        $statement->bindValue('date', date('Y-m-d', strtotime($data['date'])));
        $statement->bindValue('message', $data['message'], \PDO::PARAM_STR);

        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
    }

    public function selectAll(): array
    {
        return parent::selectAll();
    }
}
