<?php

session_start();
// Inclure la connexion à la base de données
require_once '../db.php';
require_once '../Model/user.php'; // Assurez-vous que le chemin est correct
require_once '../Controller/AuthController.php'; // Assurez-vous que le chemin est correct



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $authController = new AuthController($connexion);

    if ($authController->login($username, $password)) {
        header("Location: ../View/dashboard.php");
        exit();
    } else {
        echo "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>
