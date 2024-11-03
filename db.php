<?php

    $user = "root";
    $pass = "";
    $dbName = "mediatheque";

    try {

        $connexion = new \PDO("mysql:host=127.0.0.1;dbname=$dbName;charset=UTF8", $user, $pass);
        $connexion->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION); 
    } catch (\PDOException $exception) {
        echo 'Erreur lors de la connexion à la base de données : ' . $exception->getMessage();
        exit;
    }


?>