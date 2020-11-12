<?php

namespace App\Controller;

use App\Model\BicycleManager;
use App\Model\ReservationManager;

class AdminBicycleController extends AbstractController
{
    public const TABLE = 'bike';

    protected const TABLE1 = 'reservation';

    public function index()
    {
        $adminBikeManager = new BicycleManager();
        $bikes = $adminBikeManager->selectAllWithCategories();
        $error = "";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = array_map('trim', $_POST);
            $reservationManager = new ReservationManager('reservation');
            if ($reservationManager->isReservedBike((int)$data['id'])) {
                $error = "Ce vélo est réservé, il est donc impossible de le supprimer !";
            } else {
                $bicycleManager = new BicycleManager();
                $bicycleManager->delete((int)$data['id']);
                header('Location:/AdminBicycle/index');
            }
        }
        return $this->twig->render('Admin/bikes.html.twig', ['error' => $error, 'bikes' => $bikes]);
    }
}
