<?php

namespace App\Controller;

use App\Model\CategoryManager;

class AdminCategoryController extends AbstractController
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
        $adminCategoryManager = new CategoryManager();
        $categories = $adminCategoryManager->selectAll();
        return $this->twig->render('Admin/indexcategory.html.twig', [ 'categories' => $categories]);
    }
}

