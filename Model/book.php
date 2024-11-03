<?php
require_once 'media.php'; // Inclure Media pour pouvoir l'étendre

class Book extends Media {
    private int $pages;
    private int $id;

    public function __construct(string $titre, string $auteur, bool $disponible, int $pages) {
        parent::__construct($titre, $auteur, $disponible);
        $this->pages = $pages;
        
    }

    public function getPageNumber(): int {
        return $this->pages;
    }

    // Méthode statique pour créer un objet Book à partir d'un tableau
    public static function fromArray(array $data): Book {
        return new Book($data['titre'], $data['auteur'], (bool)$data['disponible'], $data['pages']);
    }

    public function getId(): int { // Ajoutez cette méthode
        return $this->id;
    }
}
?>
