<?php
require '../db.php';

function createFixtures($connexion) {
    if (!$connexion) {
        echo "Erreur : la connexion à la base de données est nulle.";
        return;
    }

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

    $books = [
        ['titre' => 'Le Petit Prince', 'auteur' => 'Antoine de Saint-Exupéry', 'disponible' => 1, 'pages' => 96],
        ['titre' => '1984', 'auteur' => 'George Orwell', 'disponible' => 1, 'pages' => 328],
        ['titre' => 'Moby Dick', 'auteur' => 'Herman Melville', 'disponible' => 0, 'pages' => 635],
    ];

    foreach ($books as $book) {
        $stmt = $connexion->prepare("INSERT INTO media (titre, auteur, disponible, type) VALUES (:titre, :auteur, :disponible, 'livre')");
        $stmt->execute([
            ':titre' => $book['titre'],
            ':auteur' => $book['auteur'],
            ':disponible' => $book['disponible'],
        ]);
        $mediaId = $connexion->lastInsertId();

        $stmtLivre = $connexion->prepare("INSERT INTO livre (id, pages) VALUES (:id, :pages)");
        $stmtLivre->execute([
            ':id' => $mediaId,
            ':pages' => $book['pages'],
        ]);
    }

    $movies = [
        ['titre' => 'Inception', 'auteur' => 'Christopher Nolan', 'disponible' => 1, 'duree' => 2.5, 'genre' => 'Action'],
        ['titre' => 'The Matrix', 'auteur' => 'Lana Wachowski', 'disponible' => 1, 'duree' => 2.0, 'genre' => 'Sci-Fi'],
    ];

    foreach ($movies as $movie) {
        $stmt = $connexion->prepare("INSERT INTO media (titre, auteur, disponible, type) VALUES (:titre, :auteur, :disponible, 'film')");
        $stmt->execute([
            ':titre' => $movie['titre'],
            ':auteur' => $movie['auteur'],
            ':disponible' => $movie['disponible'],
        ]);
        $mediaId = $connexion->lastInsertId();

        $stmtGenre = $connexion->prepare("SELECT id FROM genre WHERE nom = :genre");
        $stmtGenre->execute([':genre' => $movie['genre']]);
        $genreId = $stmtGenre->fetchColumn();

        $stmtFilm = $connexion->prepare("INSERT INTO film (id, duree, genre_id) VALUES (:id, :duree, :genre_id)");
        $stmtFilm->execute([
            ':id' => $mediaId,
            ':duree' => $movie['duree'],
            ':genre_id' => $genreId,
        ]);
    }

    $albums = [
        ['titre' => 'Abbey Road', 'auteur' => 'The Beatles', 'disponible' => 0, 'nombreChansons' => 17, 'label' => 'Apple Records'],
    ];

    foreach ($albums as $album) {
        $stmt = $connexion->prepare("INSERT INTO media (titre, auteur, disponible, type) VALUES (:titre, :auteur, :disponible, 'album')");
        $stmt->execute([
            ':titre' => $album['titre'],
            ':auteur' => $album['auteur'],
            ':disponible' => $album['disponible'],
        ]);
        $mediaId = $connexion->lastInsertId();

        $stmtAlbum = $connexion->prepare("INSERT INTO album (id, nombreChansons, label) VALUES (:id, :nombreChansons, :label)");
        $stmtAlbum->execute([
            ':id' => $mediaId,
            ':nombreChansons' => $album['nombreChansons'],
            ':label' => $album['label'],
        ]);
    }

    echo "Fixtures insérées avec succès.";
}

createFixtures($connexion);
?>
