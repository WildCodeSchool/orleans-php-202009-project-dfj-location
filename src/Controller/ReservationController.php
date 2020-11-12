<?php

namespace App\Controller;

use App\Model\ReservationManager;
use App\Model\BicycleManager;

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
                $reservationManager = new ReservationManager('reservation');
                $id = $reservationManager->insert($data);
                header("Location:/reservation/done/" . $id);
            }
        }
        return $this->twig->render('Reservation/reservation.html.twig', ['errors' => $errors ?? [],
            'data' => $data , 'bikes' => $bikes]);
    }

    public function done(int $id)
    {
        $reservationManager = new ReservationManager('reservation');
        $reservation = $reservationManager->selectOneById($id);
        return $this->twig->render('Reservation/thanks.html.twig', ['data' => $reservation]);
    }
    public function select()
    {
        $select = new ReservationManager('bike');
        $id = $select->selectAll();
        return $this->twig->render('Reservation/reservation.html.twig', ['data' => $id]);
    }

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
        if (empty($data['email'])) {
            $errors[] = "L'adresse email est obligatoire pour réserver";
        }
        if (empty($data['bike'])) {
            $errors[] = "Le choix du vélo est obligatoire";
        }
        if (empty($data['number'] && $data['number'] >= 0)) {
            $errors[] = "Le nombre doit être positif";
        }
        if (empty($data['last'])) {
            $errors[] = "La durée de location est obligatoire";
        }
        return $errors;
    }
}
