<?php

namespace App\Controller;

use App\Model\AdminBicycleManager;
use App\Model\CategoryManager;

class AdminBicycleController extends AbstractController
{
    public const TABLE = 'bike';

    public function editorBike()
    {
        $categoryManager = new CategoryManager();
        $categories = $categoryManager->selectAll();
        $bike = [];
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $bike = array_map('trim', $_POST);
            $errors = $this->validateBike($bike);

            if (empty($errors)) {
                $adminBicycleManager = new AdminBicycleManager();
                $adminBicycleManager->update($bike);
                header("location:/AdminBicycle/index");
            }
        }
        return $this->twig->render('Admin/editor-bike.html.twig', ['errors' => $errors ?? [],
            'bike' => $bike, 'categories' => $categories

        ]);
    }
    /**
     * @SuppressWarnings(PHPMD)
     * @param array $bike
     * @return array
     */

    private function validateBike(array $bike)
    {
        $errors = [];
        if (empty($bike['name'])) {
            $errors[] = 'Le nom du vélo est obligatoire.';
        }
        $length = 100;
        if (strlen($bike['name']) > $length) {
            $errors[] = 'Le nom du vélo ne doit pas dépasser ' . $length . ' caractères.';
        }

        if (empty($bike['weight']) || $bike['weight'] < 0) {
            $errors[] = 'Le poids du vélo est obligatoire et doit être positif.';
        }

        if (empty($bike['stock']) || $bike['stock'] < 0) {
            $errors[] = 'le nombre de vélo ne peut être inférieur à 0';
        }

        if (!empty($bike['category_id'] === 'catégorie')) {
            $errors[] = 'La selection de la catégorie est obligatoire.';
        }

        if (empty($bike['image']) || !filter_var($bike['image'], FILTER_VALIDATE_URL)) {
            $errors[] = 'L\'image doit être une URL valide et le champs ne doit pas être vide.';
        }

        return $errors;
    }
}
