<?php

class Movie extends Media {
    private float $duration;
    private Genre $genre;
    private int $id;

    public function __construct(string $titre, string $auteur, bool $disponible, float $duration, Genre $genre) {
        parent::__construct($titre, $auteur, $disponible);
        $this->duration = $duration;
        $this->genre = $genre;
    }

    public function getDuration(): float {
        return $this->duration;
    }

    public function getGenre(): Genre {
        return $this->genre;
    }

    public function getType(): string {
        return 'film'; // or another suitable string for movies
    }

    public function getId(): int { // Ajoutez cette méthode
        return $this->id;
    }
}
