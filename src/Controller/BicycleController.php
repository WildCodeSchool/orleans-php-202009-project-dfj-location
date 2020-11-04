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
     *
     * @param string $category
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function category(string $category)
    {
        $bicycleManager = new BicycleManager();
        $bicycles = $bicycleManager->selectByCategory($category);

        return $this->twig->render('Bicycle/index.html.twig', ['bicycles' => $bicycles, 'category' => $category]);
    }
}
