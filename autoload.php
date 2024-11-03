<?php

spl_autoload_register(function ($class_name) {
    $file = __DIR__ . '/model/' . $class_name . '.php';

    // Vérifie si le fichier existe
    if (file_exists($file)) {
        require $file;
    } else {
        $file = __DIR__ . '/controller/' . $class_name . '.php';
        if (file_exists($file)) {
            require $file;
        }
    }
});
?>