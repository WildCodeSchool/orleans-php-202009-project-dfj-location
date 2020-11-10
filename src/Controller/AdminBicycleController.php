<?php

namespace App\Controller;

use App\Model\BicycleManager;

class AdminBicycleController extends AbstractController
{

    public function index()
    {
        $adminBikeManager = new BicycleManager();
        $bikes = $adminBikeManager->selectAllWithCategories();

        return $this->twig->render('Admin/bikes.html.twig', ['bikes' => $bikes]);
    }
}
