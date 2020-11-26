<?php

namespace App\Controller;

use App\Model\AdminBicycleManager;
use App\Model\CategoryManager;
use App\Model\BicycleManager;
use App\Model\ReservationManager;

class AdminBicycleController extends AbstractController
{

    public const MAX_FILE_SIZE = 1000000;
    public const AUTHORIZED_MIMES = ['image/jpeg', 'image/png'];

    /**
     * Display item creation page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $adminBikeManager = new BicycleManager();
        $bikes = $adminBikeManager->selectAllWithCategories();
        $reservationManager = new ReservationManager();
        $bikesReservations = $reservationManager->numberOfBikeReservation();
        return $this->twig->render('Admin/bikes.html.twig', ['BikesReservations' => $bikesReservations,
            'bikes' => $bikes]);
    }

    public function remove()
    {
        $reservationManager = new ReservationManager();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = array_map('trim', $_POST);
            $reservationManager->isReservedBike((int)$data['id']);
            $bicycleManager = new BicycleManager();
            $bicycleManager->delete((int)$data['id']);
            header('Location:/AdminBicycle/index');
        }
    }

    public function addBike()
    {
        $categoryManager = new CategoryManager();
        $categories = $categoryManager->selectAll();
        $bike = [];
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $bike = array_map('trim', $_POST);
            $errors = $this->validateBike($bike, $_FILES['image']);

            if (empty($errors)) {
                $this->uploadImage($_FILES['image']);
                $bike['image'] = $_FILES['image']['name'];
                $adminBicycleManager = new AdminBicycleManager();
                $adminBicycleManager->insert($bike);
                header("location:/AdminBicycle/index");
            }
        }
        return $this->twig->render('Admin/add-bike.html.twig', ['errors' => $errors ?? [],
            'bike' => $bike, 'categories' => $categories
        ]);
    }


    public function editBike(int $id)
    {
        $categoryManager = new CategoryManager();
        $categories = $categoryManager->selectAll();

        $adminBicycleManager = new AdminBicycleManager();
        $editBike = $adminBicycleManager->selectOneById($id);
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $bike = array_map('trim', $_POST);
            $errors = $this->validateBike($bike, $_FILES['image']);

            if (empty($errors)) {
                if (!empty($_FILES['image'])) {
                    $this->uploadImage($_FILES['image']);
                    $bike['image'] = $_FILES['image']['name'];
                } else {
                    $bike['image'] = $editBike['image'];
                }
                $adminBicycleManager = new AdminBicycleManager();
                $adminBicycleManager->update($bike, $id);
                header("location:/AdminBicycle/index");
            }
        }

        return $this->twig->render('Admin/editor-bike.html.twig', ['errors' => $errors ?? [],
            'bike' => $editBike, 'categories' => $categories]);
    }


    private function uploadImage(array $file)
    {
        $uploadDirectory = 'uploads/bikes/';
        $filename = $uploadDirectory . $file['name'];
        move_uploaded_file($file['tmp_name'], $filename);
    }

    /**
     * @SuppressWarnings(PHPMD)
     * @param array $bike
     * @param array $file
     * @return array
     */

    private function validateBike(array $bike, array $file)
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

        if (empty($bike['category_id'])) {
            $errors[] = 'La selection de la catégorie est obligatoire.';
        }

        if ($file['size'] > self::MAX_FILE_SIZE) {
            $errors[] = 'Le fichier ne doit pas excéder ' . self::MAX_FILE_SIZE / 1000000 . ' Mo';
        }
        if (!empty($file['tmp_name']) && !in_array(mime_content_type($file['tmp_name']), self::AUTHORIZED_MIMES)) {
            $errors[] = 'Ce type de fichier n\'est pas valide';
        }

        if (empty($bike['description'])) {
            $errors[] = 'La description du vélo est obligatoire.';
        }

        return $errors;
    }
}
