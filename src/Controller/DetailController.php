<?php

namespace App\Controller;

use App\Model\BicycleManager;

class DetailController extends AbstractController
{
    /**
     * Display item informations specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index(int $id)
    {
        $bicycleManager = new BicycleManager();
        $bicycles = $bicycleManager->selectOneById($id);
        $prices = $bicycleManager->duration($id);
        return $this->twig->render('Bicycle/detailsBike.html.twig', ['bicycles' => $bicycles, 'prices' => $prices
        ]);
    }
}
