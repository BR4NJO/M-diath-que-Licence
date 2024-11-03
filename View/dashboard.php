<?php 
require_once '../Model/genre.php';
require_once '../Model/media.php'; // Fichier contenant la classe Media
require_once '../Model/book.php'; // Fichier contenant la classe Book
require_once '../Model/movie.php'; // Fichier contenant la classe Movie
require_once '../Model/album.php'; // Fichier contenant la classe Album

session_start(); // Démarrer la session

// Vérifiez si l'utilisateur est connecté
$isLoggedIn = isset($_SESSION['username']);
if (!$isLoggedIn) {
    echo "Veuillez vous connecter pour accéder au contenu.";
    exit; // Arrête le script si l'utilisateur n'est pas connecté
}

$username = $_SESSION['username'];

// Classe Dashboard
class Dashboard {
    private $db;

    private function getGenreById($id): Genre {
        // Récupération des genres basés sur l'ID
        switch ($id) {
            case 1:
                return Genre::Action;
            case 2:
                return Genre::SciFi;
            case 3:
                return Genre::Drama;
            case 4:
                return Genre::Comedy;
            default:
                return Genre::Action; // Retourne une valeur par défaut ou gérez l'erreur comme vous le souhaitez
        }
    }

    public function __construct($db) {
        $this->db = $db;
    }

    public function ajouterMedia($media) {
        // Prepare the SQL statement
        $query = "INSERT INTO media (titre, auteur, type, disponible, pages, duree, genre, nombreChansons, label) 
                  VALUES (:titre, :auteur, :type, :disponible, :pages, :duree, :genre, :nombreChansons, :label)";
        
        $stmt = $this->db->prepare($query);
    
        // Bind the parameters
        $stmt->bindParam(':titre', $media->getTitre());
        $stmt->bindParam(':auteur', $media->getAuteur());
        
        // Use variables to avoid passing method calls directly
        $type = $media->getType(); 
        $stmt->bindParam(':type', $type);
        
        $isDisponible = $media->estDisponible();
        $stmt->bindParam(':disponible', $isDisponible);
    
        if ($media instanceof Book) {
            $pages = $media->getPageNumber();
            $stmt->bindParam(':pages', $pages);
            $stmt->bindValue(':duree', null);
            $stmt->bindValue(':genre', null);
            $stmt->bindValue(':nombreChansons', null);
            $stmt->bindValue(':label', null);
        } elseif ($media instanceof Movie) {
            $duration = $media->getDuration();
            $stmt->bindValue(':pages', null);
            $stmt->bindParam(':duree', $duration);
            $genre = $media->getGenre()->name; // Assuming genre is accessible like this
            $stmt->bindParam(':genre', $genre);
            $stmt->bindValue(':nombreChansons', null);
            $stmt->bindValue(':label', null);
        } elseif ($media instanceof Album) {
            $stmt->bindValue(':pages', null);
            $stmt->bindValue(':duree', null);
            $stmt->bindValue(':genre', null);
            $songCount = $media->getSongNumber();
            $stmt->bindParam(':nombreChansons', $songCount);
            $label = $media->getEditor();
            $stmt->bindParam(':label', $label);
        }
    
        try {
            if ($stmt->execute()) {
                return true; // The insertion succeeded
            } else {
                throw new Exception("Erreur lors de l'exécution de la requête.");
            }
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }

    public function getLivres($searchTerm = '') {
        $query = "SELECT m.titre, m.auteur, m.disponible, l.pages 
                  FROM media AS m 
                  JOIN livre AS l ON m.id = l.id 
                  WHERE m.type = 'livre' AND (m.titre LIKE :searchTerm OR m.auteur LIKE :searchTerm)";
        $stmt = $this->db->prepare($query);
        $searchParam = '%' . $searchTerm . '%';
        $stmt->bindParam(':searchTerm', $searchParam);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Creating Book objects
        $livres = [];
        foreach ($result as $row) {
            $livres[] = new Book($row['titre'], $row['auteur'], $row['disponible'], $row['pages']);
        }
        return $livres;
    }

    public function getFilms($searchTerm = '') {
        $query = "SELECT m.titre, m.auteur, m.disponible, f.duree, f.genre_id 
                  FROM media AS m 
                  JOIN film AS f ON m.id = f.id 
                  WHERE m.type = 'film' AND (m.titre LIKE :searchTerm OR m.auteur LIKE :searchTerm)";
        $stmt = $this->db->prepare($query);
        $searchParam = '%' . $searchTerm . '%';
        $stmt->bindParam(':searchTerm', $searchParam);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        $films = [];
        foreach ($result as $row) {
            // Récupérer l'objet Genre à partir de genre_id
            $genre = $this->getGenreById($row['genre_id']); // Récupérez l'objet Genre ici
    
            // Créer un nouvel objet Movie avec le bon genre
            $films[] = new Movie(
                $row['titre'],
                $row['auteur'],
                (bool)$row['disponible'],
                (float)$row['duree'],
                $genre // Passer l'objet Genre ici
            );
        }
        return $films;
    }
    
    public function getAlbums($searchTerm = '') {
        $query = "SELECT m.titre, m.auteur, m.disponible, a.nombreChansons, a.label 
                  FROM media AS m 
                  JOIN album AS a ON m.id = a.id 
                  WHERE m.type = 'album' AND (m.titre LIKE :searchTerm OR m.auteur LIKE :searchTerm)";
        $stmt = $this->db->prepare($query);
        $searchParam = '%' . $searchTerm . '%';
        $stmt->bindParam(':searchTerm', $searchParam);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Creating Album objects
        $albums = [];
        foreach ($result as $row) {
            $albums[] = new Album(
                $row['titre'], // Titre de l'album
                $row['auteur'], // Auteur de l'album
                (bool)$row['disponible'], // Disponibilité
                (int)$row['nombreChansons'], // Nombre de chansons
                $row['label'] // Label de l'album
            );
        }
        return $albums;
    }
}

// Inclure le fichier de connexion à la base de données
require_once '../db.php'; // Assurez-vous que le chemin est correct

// Instanciation de la classe Dashboard
$dashboard = new Dashboard($connexion);

// Récupérer le terme de recherche, s'il existe
$searchTerm = isset($_POST['search']) ? $_POST['search'] : '';

// Récupérer les données avec filtrage
$livres = $dashboard->getLivres($searchTerm);
$films = $dashboard->getFilms($searchTerm);
$albums = $dashboard->getAlbums($searchTerm);

// Le reste de votre code HTML...
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard des Médias</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .logout-btn {
            margin-bottom: 20px;
        }
        .search-bar {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>Bienvenue, <?php echo htmlspecialchars($username); ?> !</h1>

    <div class="search-bar">
        <form method="POST">
            <input type="text" name="search" placeholder="Rechercher un livre, film ou album" value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button type="submit">Rechercher</button>
        </form>
    </div>

    <!-- Bouton de déconnexion -->
    <form method="POST" action="logout.php" class="logout-btn">
        <button type="submit">Déconnexion</button>
    </form>

    <div class="actions">
        <a href="add.php" class="btn-add-media">Ajouter un Média</a>
    </div>

    <h1>Liste des Médias</h1>

    <?php if (!empty($livres)): ?>
        <h2>Livres</h2>
        <table>
            <tr>
                <th>Titre</th>
                <th>Auteur</th>
                <th>Disponible</th>
                <th>Pages</th>
            </tr>
            <?php foreach ($livres as $livre): ?>
                <tr>
                    <td><?php echo htmlspecialchars($livre->getTitre()); ?></td>
                    <td><?php echo htmlspecialchars($livre->getAuteur()); ?></td>
                    <td><?php echo $livre->estDisponible() ? 'Oui' : 'Non'; ?></td>
                    <td><?php echo htmlspecialchars($livre->getPageNumber()); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <h2>La recherche ne concerne pas un livre.</h2>
    <?php endif; ?>

    <?php if (!empty($films)): ?>
        <h2>Films</h2>
        <table>
            <tr>
                <th>Titre</th>
                <th>Auteur</th>
                <th>Disponible</th>
                <th>Durée</th>
                <th>Genre</th>
            </tr>
            <?php foreach ($films as $film): ?>
                <tr>
                    <td><?php echo htmlspecialchars($film->getTitre()); ?></td>
                    <td><?php echo htmlspecialchars($film->getAuteur()); ?></td>
                    <td><?php echo $film->estDisponible() ? 'Oui' : 'Non'; ?></td>
                    <td><?php echo htmlspecialchars($film->getDuration()); ?></td>
                    <td><?php echo htmlspecialchars($film->getGenre()->name); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <h2>La recherche ne concerne pas un film.</h2>
    <?php endif; ?>

    <?php if (!empty($albums)): ?>
        <h2>Albums</h2>
        <table>
            <tr>
                <th>Titre</th>
                <th>Auteur</th>
                <th>Disponible</th>
                <th>Nombre de Chansons</th>
                <th>Label</th>
            </tr>
            <?php foreach ($albums as $album): ?>
                <tr>
                    <td><?php echo htmlspecialchars($album->getTitre()); ?></td>
                    <td><?php echo htmlspecialchars($album->getAuteur()); ?></td>
                    <td><?php echo $album->estDisponible() ? 'Oui' : 'Non'; ?></td>
                    <td><?php echo htmlspecialchars($album->getSongNumber()); ?></td>
                    <td><?php echo htmlspecialchars($album->getEditor()); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <h2>La recherche ne concerne pas un album.</h2>
    <?php endif; ?>
</body>
</html>
