<?php

namespace App\Controller;

use App\Model\ReservationManager;
use App\Model\BicycleManager;
use Cassandra\Date;

class ReservationController extends AbstractController
{
    public function booking()
    {
        $bikeManager = new BicycleManager();
        $bikes = $bikeManager->selectAllWithCategories();
        $errors = [];
        $data = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = array_map('trim', $_POST);
            $errors = $this->validate($data);
            if (empty($errors)) {
                $reservationManager = new ReservationManager();
                $id = $reservationManager->insert($data);
                header("Location:/reservation/done/" . $id);
            }
        }
        return $this->twig->render('Reservation/reservation.html.twig', ['errors' => $errors ?? [],
            'data' => $data , 'bikes' => $bikes]);
    }

    public function done(int $id)
    {
        $reservationManager = new ReservationManager();
        $reservation = $reservationManager->selectOneById($id);
        return $this->twig->render('Reservation/thanks.html.twig', ['data' => $reservation]);
    }

    public function select()
    {
        $select = new ReservationManager();
        $id = $select->selectAll();
        return $this->twig->render('Reservation/reservation.html.twig', ['data' => $id]);
    }
    /**
     * @SuppressWarnings(PHPMD)
     * @param array $data
     * @return array
     */
    private function validate(array $data)
    {
        $errors = [];
        if (empty($data['lastname'])) {
            $errors[] = "Entrez votre nom s.v.p";
        }
        if (empty($data['firstname'])) {
            $errors[] = "Entrez votre prénom s.v.p";
        }
        if (empty($data['tel'])) {
            $errors[] = "Le numéro de telephone est obligatoire pour réserver";
        }
        /*$nameMaxLength = 100;
        if ($data['firstname'] || $data['lastname'] > $nameMaxLength) {
            $errors[] = "le nom et le prénom ne doivent pas dépasser 100 caractères";
        }
        $phoneMaxLength = 20;
        if ($data['tel'] > $phoneMaxLength) {
            $errors[] = "le numero de télephone ne doit pas contenir plus de 20 caractères";
        }*/
        if (empty($data['tel'])) {
            $errors[] = "Le numéro de télephone est obligatoire pour réserver";
        }
        if (empty($data['email'])) {
            $errors[] = "L'adresse email est obligatoire pour réserver";
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "le format de l'email est invalide";
        }
        $today =  date('Y-m-d');
        if ($data['date'] < $today) {
            $errors[] = "La date selectionnée ne peut être inférieure à la date actuelle";
        }
        if (empty($data['bike'])) {
            $errors[] = "Le choix du vélo est obligatoire";
        }
        if (empty($data['number'] && $data['number'] >= 0)) {
            $errors[] = "Le nombre doit être positif";
        }
        return $errors;
    }
}
