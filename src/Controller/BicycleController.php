<?php

namespace App\Controller;

use App\Model\BicycleManager;

/**
 * Class BicycleController
 *
 */
class BicycleController extends AbstractController
{
    /**
     * Display bicycle listing
     * @param int $category
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */

    public function category(int $category)
    {
        $bicycleManager = new BicycleManager();
        $bicycles = $bicycleManager->selectByCategory($category);
        return $this->twig->render('Bicycle/index.html.twig', ['bicycles' => $bicycles]);
    }
    public function show(int $id)
    {
        $bicycleManager = new BicycleManager();
        $bicycles = $bicycleManager->selectOneById($id);
        $prices = $bicycleManager->duration($id);
        return $this->twig->render('Bicycle/detailsBike.html.twig', ['bicycles' => $bicycles, 'prices' => $prices
        ]);
    }
}
