<?php
require '../db.php';

function createFixtures($connexion) {
    if (!$connexion) {
        echo "Erreur : la connexion à la base de données est nulle.";
        return;
    }

    // Insérer des genres
    $genres = [
        'Action',
        'Sci-Fi',
        'Drama',
        'Comedy'
    ];

    foreach ($genres as $genre) {
        $stmt = $connexion->prepare("INSERT INTO genre (nom) VALUES (:nom)");
        $stmt->execute([':nom' => $genre]);
    }

    // Exemple d'insertion pour des livres
    $books = [
        ['titre' => 'Le Petit Prince', 'auteur' => 'Antoine de Saint-Exupéry', 'disponible' => 1, 'pages' => 96],
        ['titre' => '1984', 'auteur' => 'George Orwell', 'disponible' => 1, 'pages' => 328],
        ['titre' => 'Moby Dick', 'auteur' => 'Herman Melville', 'disponible' => 0, 'pages' => 635],
    ];

    foreach ($books as $book) {
        // Insérer dans la table media
        $stmt = $connexion->prepare("INSERT INTO media (titre, auteur, disponible, type) VALUES (:titre, :auteur, :disponible, 'livre')");
        $stmt->execute([
            ':titre' => $book['titre'],
            ':auteur' => $book['auteur'],
            ':disponible' => $book['disponible'],
        ]);
        $mediaId = $connexion->lastInsertId();

        // Insérer dans la table livre
        $stmtLivre = $connexion->prepare("INSERT INTO livre (id, pages) VALUES (:id, :pages)");
        $stmtLivre->execute([
            ':id' => $mediaId,
            ':pages' => $book['pages'],
        ]);
    }

    // Exemple d'insertion pour des films
    $movies = [
        ['titre' => 'Inception', 'auteur' => 'Christopher Nolan', 'disponible' => 1, 'duree' => 2.5, 'genre' => 'Action'],
        ['titre' => 'The Matrix', 'auteur' => 'Lana Wachowski', 'disponible' => 1, 'duree' => 2.0, 'genre' => 'Sci-Fi'],
    ];

    foreach ($movies as $movie) {
        // Insérer dans la table media
        $stmt = $connexion->prepare("INSERT INTO media (titre, auteur, disponible, type) VALUES (:titre, :auteur, :disponible, 'film')");
        $stmt->execute([
            ':titre' => $movie['titre'],
            ':auteur' => $movie['auteur'],
            ':disponible' => $movie['disponible'],
        ]);
        $mediaId = $connexion->lastInsertId();

        // Récupérer l'ID du genre
        $stmtGenre = $connexion->prepare("SELECT id FROM genre WHERE nom = :genre");
        $stmtGenre->execute([':genre' => $movie['genre']]);
        $genreId = $stmtGenre->fetchColumn();

        // Insérer dans la table film
        $stmtFilm = $connexion->prepare("INSERT INTO film (id, duree, genre_id) VALUES (:id, :duree, :genre_id)");
        $stmtFilm->execute([
            ':id' => $mediaId,
            ':duree' => $movie['duree'],
            ':genre_id' => $genreId,
        ]);
    }

    // Exemple d'insertion pour des albums
    $albums = [
        ['titre' => 'Abbey Road', 'auteur' => 'The Beatles', 'disponible' => 0, 'nombreChansons' => 17, 'label' => 'Apple Records'],
    ];

    foreach ($albums as $album) {
        // Insérer dans la table media
        $stmt = $connexion->prepare("INSERT INTO media (titre, auteur, disponible, type) VALUES (:titre, :auteur, :disponible, 'album')");
        $stmt->execute([
            ':titre' => $album['titre'],
            ':auteur' => $album['auteur'],
            ':disponible' => $album['disponible'],
        ]);
        $mediaId = $connexion->lastInsertId();

        // Insérer dans la table album
        $stmtAlbum = $connexion->prepare("INSERT INTO album (id, nombreChansons, label) VALUES (:id, :nombreChansons, :label)");
        $stmtAlbum->execute([
            ':id' => $mediaId,
            ':nombreChansons' => $album['nombreChansons'],
            ':label' => $album['label'],
        ]);
    }

    echo "Fixtures insérées avec succès.";
}

// Appelle la fonction pour insérer les fixtures
createFixtures($connexion);
?>
