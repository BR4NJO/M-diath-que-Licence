<?php

class Media
{
    private string $titre;
    private string $auteur;
    private bool $disponible;

    public function __construct(string $titre, string $auteur, bool $disponible) {
        if (empty($titre) || empty($auteur)) {
            throw new InvalidArgumentException("Le titre et l'auteur ne peuvent pas être vides.");
        }
        $this->titre = $titre;
        $this->auteur = $auteur;
        $this->disponible = $disponible;
    }

    public function emprunter(): void {
        if ($this->disponible) {
            echo "Le média '{$this->titre}' de '{$this->auteur}' a été emprunté.\n";
            $this->disponible = false;
        } else {
            echo "Désolé, '{$this->titre}' n'est pas disponible pour l'emprunt.\n";
        }
    }

    public function rendre(): void {
        if (!$this->disponible) {
            echo "Le média '{$this->titre}' de '{$this->auteur}' a été rendu.\n";
            $this->disponible = true;
        } else {
            echo "Le média '{$this->titre}' était déjà disponible.\n";
        }
    }

    public function estDisponible(): bool {
        return $this->disponible;
    }

    public function getTitre(): string {
        return $this->titre;
    }

    public function getAuteur(): string {
        return $this->auteur;
    }

    public function __toString(): string {
        return "Titre: {$this->titre}, Auteur: {$this->auteur}, Disponible: " . ($this->disponible ? "Oui" : "Non");
    }
}
?>
