<?php

/**
 * Created by PhpStorm.
 * User: aurelwcs
 * Date: 08/04/19
 * Time: 18:40
 */

namespace App\Controller;

use App\Model\HomeManager;

class HomeController extends AbstractController
{

    /**
     * Display home page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $homeManager = new HomeManager();
        $newBikes = $homeManager->selectNewestBikes();
        return $this->twig->render('Home/index.html.twig', [
            'newBikes' => $newBikes,
        ]);
    }

    public function legalMentions()
    {
        return $this->twig->render('Home/legal-mentions.html.twig');
    }

    public function aboutus()
    {
        return $this->twig->render('Home/aboutus.html.twig');
    }
}
