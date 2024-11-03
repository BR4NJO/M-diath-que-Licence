<?php

session_start();
require_once '../db.php';
require_once '../Model/user.php';
require_once '../Controller/AuthController.php'; 


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
