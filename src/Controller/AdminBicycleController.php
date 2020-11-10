<?php


namespace App\Controller;


use App\Model\BicycleManager;

class AdminBicycleController extends AbstractController
{

        public function index()
    {
        $AdminBikeManager = new BicycleManager();
        $bikes = $AdminBikeManager->selectAllWithCategories();

        return $this->twig->render('Admin/bikes.html.twig', ['bikes' => $bikes]);
    }




}