<?php

namespace App\Controller;

use App\Model\ReservationManager;

class ReservationController extends AbstractController
{
    public function iDo()
    {
        $data = array_map('trim', $_POST);
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
            if (empty($data['veloselect'])) {
                $errors[] = "Le choix du vélo est obligatoire";
            }
            if (empty($data['last'])) {
                $errors[] = "La durée de location est obligatoire";
            }
            if (empty($errors)) {
                $reservationManager = new ReservationManager('reservation');
                $id = $reservationManager->insert($data);
                header("Location:/reservation/done/" . $id);
            }
        }
        return $this->twig->render('Reservation/reservation.html.twig', ['errors' => $errors, 'data' => $data]);
    }

    public function done(int $id)
    {
        $reservationManager = new ReservationManager('reservation');
        $reservation = $reservationManager->selectOneById($id);

        return $this->twig->render('Reservation/thanks.html.twig', ['data' => $reservation]);
    }
}
