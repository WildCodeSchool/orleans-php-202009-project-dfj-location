<?php

namespace App\Controller;

use App\Model\ReservationManager;

/**
 * Class AdminReservationController
 *
 */
class AdminReservationController extends AbstractController
{
    /**
     * Display reservation listing
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $reservationManager = new ReservationManager();
        $reservations = $reservationManager->selectNotValidated();

        return $this->twig->render('Admin/index.html.twig', ['reservations' => $reservations]);
    }

    /**
     * Display reservation
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function reservation(int $id)
    {
        $reservationManager = new ReservationManager();
        $reservation = $reservationManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = array_map('trim', $_POST);
            $success = false;
            if ($data['action'] == 'Accepter') {
                $success = $reservationManager->acceptReservation((int)$data['id']);
            } else {
                $success = $reservationManager->refuseReservation((int)$data['id']);
            }
            if ($success) {
                header("Location:/adminReservation/index");
            }
        }

        return $this->twig->render('Admin/reservation.html.twig', ['reservation' => $reservation]);
    }
}
