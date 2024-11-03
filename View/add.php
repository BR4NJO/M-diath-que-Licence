<?php
require "../db.php";

function getGenres($connexion) {
    $stmt = $connexion->query("SELECT id, nom FROM genre");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$genres = getGenres($connexion);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = $_POST['titre'];
    $auteur = $_POST['auteur'];
    $disponible = isset($_POST['disponible']) ? 1 : 0;
    $type = $_POST['type'];
    $genre_id = $_POST['genre_id'] ?? null;
    $nombreChansons = $_POST['nombreChansons'] ?? null;
    $pages = $_POST['pages'] ?? null;
    $duree = $_POST['duree'] ?? null;

    try {
        $stmt = $connexion->prepare("INSERT INTO media (titre, auteur, disponible, type) VALUES (:titre, :auteur, :disponible, :type)");
        $stmt->execute([
            ':titre' => $titre,
            ':auteur' => $auteur,
            ':disponible' => $disponible,
            ':type' => $type
        ]);

        $mediaId = $connexion->lastInsertId();

        if ($type === 'livre') {
            $stmt = $connexion->prepare("INSERT INTO livre (id, pages) VALUES (:id, :pages)");
            $stmt->execute([':id' => $mediaId, ':pages' => $pages]);
        } elseif ($type === 'film') {
            $stmt = $connexion->prepare("INSERT INTO film (id, duree, genre_id) VALUES (:id, :duree, :genre_id)");
            $stmt->execute([':id' => $mediaId, ':duree' => $duree, ':genre_id' => $genre_id]);
        } elseif ($type === 'album') {
            $stmt = $connexion->prepare("INSERT INTO album (id, nombreChansons, label) VALUES (:id, :nombreChansons, :label)");
            $stmt->execute([':id' => $mediaId, ':nombreChansons' => $nombreChansons, ':label' => $_POST['label']]);
        }

        echo "Le média a été ajouté avec succès !";
    } catch (Exception $e) {
        echo "Erreur lors de l'ajout du média : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Média</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            width: 25rem;
        }
        .container {
            background-color: #fff;
            padding: 2em;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #555;
        }
        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        input[type="checkbox"] {
            margin-left: 10px;
        }
        button[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-top: 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        button[type="submit"]:hover {
            background-color: #0056b3;
        }
        .success-message {
            color: green;
            text-align: center;
            margin-top: 15px;
        }
        #livreFields, #filmFields, #albumFields {
            display: none;
        }
    </style>
    <script>
        function updateFormFields() {
            const type = document.getElementById('type').value;
            document.getElementById('livreFields').style.display = type === 'livre' ? 'block' : 'none';
            document.getElementById('filmFields').style.display = type === 'film' ? 'block' : 'none';
            document.getElementById('albumFields').style.display = type === 'album' ? 'block' : 'none';
        }
    </script>
</head>
<body>
    <h2>Ajouter un Média</h2>
    <form method="POST">
        <label for="titre">Titre :</label>
        <input type="text" id="titre" name="titre" required><br>

        <label for="auteur">Auteur :</label>
        <input type="text" id="auteur" name="auteur" required><br>

        <label for="disponible">Disponible :</label>
        <input type="checkbox" id="disponible" name="disponible" value="1"><br>

        <label for="type">Type :</label>
        <select id="type" name="type" onchange="updateFormFields()" required>
            <option value="livre">Livre</option>
            <option value="film">Film</option>
            <option value="album">Album</option>
        </select><br>

        <!-- Champs spécifiques pour les livres -->
        <div id="livreFields" style="display: none;">
            <label for="pages">Nombre de pages :</label>
            <input type="number" id="pages" name="pages" min="1"><br>
        </div>

        <!-- Champs spécifiques pour les films -->
        <div id="filmFields" style="display: none;">
            <label for="duree">Durée (en heures) :</label>
            <input type="number" id="duree" name="duree" step="0.1" min="0"><br>

            <label for="genre_id">Genre :</label>
            <select id="genre_id" name="genre_id">
                <?php foreach ($genres as $genre): ?>
                    <option value="<?= $genre['id'] ?>"><?= $genre['nom'] ?></option>
                <?php endforeach; ?>
            </select><br>
        </div>

        <!-- Champs spécifiques pour les albums -->
        <div id="albumFields" style="display: none;">
            <label for="nombreChansons">Nombre de chansons :</label>
            <input type="number" id="nombreChansons" name="nombreChansons" min="1"><br>

            <label for="label">Label :</label>
            <input type="text" id="label" name="label"><br>
        </div>

        <button type="submit">Ajouter</button>
    </form>

    <div class="actions">
        <a href="dashboard.php" class="btn-add-media">Retour à la page précédente</a>
    </div>

    <script>
        // Initialiser l'affichage en fonction du type sélectionné par défaut
        updateFormFields();
    </script>
</body>
</html>
