<?php
require_once 'Media.php'; 

class Album extends Media {
    private int $songNumber;
    private string $editor;
    private int $id;

    public function __construct(string $titre, string $auteur, bool $disponible, int $songNumber, string $editor) {
        parent::__construct($titre, $auteur, $disponible);
        $this->songNumber = $songNumber;
        $this->editor = $editor;
    }

    public function getSongNumber(): int {
        return $this->songNumber;
    }

    public function getEditor(): string {
        return $this->editor;
    }

    public function getType(): string {
        return 'album'; 
    }

    public function getId(): int { 
        return $this->id;
    }
}
?>
