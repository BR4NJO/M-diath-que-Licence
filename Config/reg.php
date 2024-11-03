<?php
session_start();

require_once '../db.php';
require_once '../Model/user.php'; 
require_once '../Controller/AuthController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

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
