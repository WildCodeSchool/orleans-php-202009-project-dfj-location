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

        return $this->twig->render('Admin/bikes.html.twig', ['bikes' => $bikes]);
    }

    public function delete(int $id)
    {
        $error = "";
        $reservationManager = new ReservationManager('reservation');
        if ($reservationManager->isReservedBike($id)) {
            $error = "Ce vélo est réservé, il est donc impossible de le supprimer";
        } else {
            $bicycleManager = new BicycleManager();
            $bicycleManager->delete($id);
            header('Location:/AdminBicycle/index');
        }
        return $error;
    }
}
