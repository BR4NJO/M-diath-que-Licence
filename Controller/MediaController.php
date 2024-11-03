<?php

class MediaController {
    private $connexion; // Propriété pour la connexion
    private array $medias = []; // Pour stocker les médias

    public function __construct($connexion) {
        $this->connexion = $connexion; // Initialiser la connexion
        $this->loadMedia(); // Charger les médias à l'initialisation
    }

    private function loadMedia(): void {
        $this->medias = []; // Réinitialiser le tableau des médias
    
        // Charger les livres
        $stmt = $this->connexion->query("SELECT media.titre, media.auteur, media.disponible, livre.pages FROM media JOIN livre ON media.id = livre.id");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $livre = new Book($row['titre'], $row['auteur'], (bool)$row['disponible'], (int)$row['pages']);
            $this->medias[] = $livre;
        }
        
    
        // Charger les films
        $stmt = $this->connexion->query("SELECT media.titre, media.auteur, media.disponible, film.duree, film.genre_id FROM media JOIN film ON media.id = film.id");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $genre = Genre::from($row['genre_id']); 
            $film = new Movie($row['titre'], $row['auteur'], (bool)$row['disponible'], (float)$row['duree'], $genre);
            $this->medias[] = $film;
        }
    
        // Charger les albums
        $stmt = $this->connexion->query("SELECT media.titre, media.auteur, media.disponible, album.nombreChansons, album.label FROM media JOIN album ON media.id = album.id");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $album = new Album($row['titre'], $row['auteur'], (bool)$row['disponible'], (int)$row['nombreChansons'], $row['label']);
            $this->medias[] = $album;
        }
    }

    public function getLivres(): array {
        return array_filter($this->medias, fn($media) => $media instanceof Book);
    }

    public function getFilms(): array {
        return array_filter($this->medias, fn($media) => $media instanceof Movie);
    }

    public function getAlbums(): array {
        return array_filter($this->medias, fn($media) => $media instanceof Album);
    }
}
