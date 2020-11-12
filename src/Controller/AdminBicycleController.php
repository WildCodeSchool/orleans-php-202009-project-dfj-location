<?php

namespace App\Controller;

use App\Model\BicycleManager;

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
            $bicycleManager = new BicycleManager();
            $bicycleManager->delete($id);
            header('Location:/AdminBicycle/index');
    }
}
