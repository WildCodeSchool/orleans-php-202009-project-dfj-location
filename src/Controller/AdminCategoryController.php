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
        $error = '';
        $adminCategoryManager = new CategoryManager();
        $categories = $adminCategoryManager->selectAll();
        $error = $this->remove();
        return $this->twig->render('Admin/indexCategory.html.twig', ['error' => $error,
            'categories' => $categories]);
    }

    public function add()
    {
        $categoryManager = new CategoryManager();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $category = array_map('trim', $_POST);
            $errors = [];
            if (empty($category['name'])) {
                $errors[] = 'Vous devez choisir un nom pour cette catégorie.';
            }
            $length = 100;
            if (strlen($category['name']) > $length) {
                $errors = 'Le nom de la catégorie ne doit pas dépasser ' . $length . '.';
            }
            if (empty($errors)) {
                $categoryManager->insert($category);
                header('Location: /AdminCategory/index');
            }
        }

        return $this->twig->render('Admin/addCategory.html.twig', [
            'errors' => $errors ?? [],
            'category' => $category ?? [],
        ]);
    }

    public function edit(int $id)
    {
        $categoryManager = new CategoryManager();
        $category = $categoryManager->selectOneById($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $category['name'] = trim($_POST['name']);
            $errors = [];
            if (empty($category['name'])) {
                $errors[] = 'Vous devez choisir un nom pour cette catégorie.';
            }
            $length = 100;
            if (strlen($category['name']) > $length) {
                $errors = 'Le nom de la catégorie ne doit pas dépasser ' . $length . '.';
            }
            if (empty($errors)) {
                $categoryManager->update($category);
                header('Location: /AdminCategory/index');
            }
        }

        return $this->twig->render('Admin/editCategory.html.twig', [
            'errors' => $errors ?? [],
            'category' => $category ?? [],
        ]);
    }

    public function remove()
    {
        $error = "";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = array_map('trim', $_POST);
            $categoryManager = new CategoryManager();
            if ($categoryManager->hasBike((int)$data['id'])) {
                $error = "Cette catégorie est utilisée, il est donc impossible de la supprimer !";
            } else {
                $categoryManager->delete((int)$data['id']);
                header('Location:/AdminCategory/index');
            }
            return $error;
        }
    }
}
