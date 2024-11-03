<?php
session_start();

// Inclure la connexion à la base de données
require_once '../db.php';
require_once '../Model/user.php'; // Assurez-vous que le chemin est correct
require_once '../Controller/AuthController.php'; // Assurez-vous que le chemin est correct

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Créez une instance d'AuthController
    $authController = new AuthController($connexion);

    try {
        if ($authController->register($username, $password)) {
            header("Location: ../View/login.php");
            exit();
        }
    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>
