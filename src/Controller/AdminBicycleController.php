<?php

namespace App\Controller;

use App\Model\BicycleManager;
use App\Model\ReservationManager;

class AdminBicycleController extends AbstractController
{
    public const TABLE = 'bike';

    public const TABLE1 = 'reservation';

    public function index()
    {
        $error = "";
        $adminBikeManager = new BicycleManager();
        $bikes = $adminBikeManager->selectAllWithCategories();
        $error = $this->remove();

        return $this->twig->render('Admin/bikes.html.twig', ['error' => $error, 'bikes' => $bikes]);
    }

    public function remove()
    {
        $error = "";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = array_map('trim', $_POST);
            $reservationManager = new ReservationManager();
            if ($reservationManager->isReservedBike((int)$data['id'])) {
                $error = "Ce vélo est réservé, il est donc impossible de le supprimer !";
            } else {
                $bicycleManager = new BicycleManager();
                $bicycleManager->delete((int)$data['id']);
                header('Location:/AdminBicycle/index');
            }
            return $error;
        }
    }
}
