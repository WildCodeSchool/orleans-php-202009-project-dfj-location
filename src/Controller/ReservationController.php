<?php

namespace App\Controller;

use App\Model\CategoryManager;
use App\Model\DurationManager;
use App\Model\PriceManager;
use App\Model\ReservationManager;
use App\Model\BicycleManager;

class ReservationController extends AbstractController
{
    public function booking()
    {
        $bikeManager = new BicycleManager();
        $bikes = $bikeManager->selectAllWithCategories();

        $durationManager = new DurationManager();
        $durations = $durationManager->selectAll();

        $categoryManager = new CategoryManager();
        $categories = $categoryManager->selectAll();

        $errors = [];
        $data = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = array_map('trim', $_POST);
            $idCategory = $bikeManager->getCategory((int)$data['bike']);
            $errors = $this->validate($data, $idCategory['category_id']);
            if (empty($errors)) {
                $reservationManager = new ReservationManager();
                $id = $reservationManager->insert($data);
                header("Location:/reservation/done/" . $id);
            }
        }
        return $this->twig->render('Reservation/reservation.html.twig', [
          'errors' => $errors ?? [],
          'data' => $data ,
          'categories' => $categories,
          'bikes' => $bikes,
          'durations' => $durations
        ]);
    }

    public function done(int $id)
    {
        $reservationManager = new ReservationManager();
        $reservation = $reservationManager->selectWithPrice($id);
        return $this->twig->render('Reservation/thanks.html.twig', ['data' => $reservation]);
    }

    /**
     * @SuppressWarnings(PHPMD)
     * @param array $data
     * @param string $idCategory
     * @return array
     */
    private function validate(array $data, string $idCategory)
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
        $nameMaxLength = 100;
        if (strlen($data['firstname']) > $nameMaxLength || strlen($data['lastname']) > $nameMaxLength) {
            $errors[] = "le nom et le prénom ne doivent pas dépasser $nameMaxLength caractères";
        }
        $phoneMaxLength = 20;
        if (strlen($data['tel']) > $phoneMaxLength) {
            $errors[] = "le numero de télephone ne doit pas contenir plus de $phoneMaxLength caractères";
        }
        if (empty($data['tel'])) {
            $errors[] = "Le numéro de télephone est obligatoire pour réserver";
        }
        if (empty($data['email'])) {
            $errors[] = "L'adresse email est obligatoire pour réserver";
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "le format de l'email est invalide";
        }
        if (empty($data['bike'])) {
            $errors[] = "Le choix du vélo est obligatoire";
        }
        if (empty($data['number'] && $data['number'] >= 0)) {
            $errors[] = "Le nombre doit être positif";
        }
        if (empty($data['duration'])) {
            $errors[] = "Le choix de la durée est obligatoire";
        }
        if (
            ($data['duration'] == DurationManager::ID_HALF_DAY || $data['duration'] == DurationManager::ID_TWO_WEEKS)
            && $idCategory == CategoryManager::ID_TANDEM
        ) {
            $errors[] = "Le " . CategoryManager::TANDEM . " ne peut être réservé " .
                DurationManager::HALF_DAY . " ou " . DurationManager::TWO_WEEKS;
        }
        if ($data['duration'] == DurationManager::ID_TWO_WEEKS && $idCategory == CategoryManager::ID_ELECTRIC) {
            $errors[] = "Le " . CategoryManager::ELECTRIC . " ne peut être réservé " . DurationManager::TWO_WEEKS;
        }
        return $errors;
    }
}
