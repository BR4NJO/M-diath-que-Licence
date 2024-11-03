<?php
session_start(); // Démarrer la session

// Détruire la session
session_unset();
session_destroy();

// Rediriger vers la page de connexion ou une autre page
header("Location: login.php");
exit();
?>
