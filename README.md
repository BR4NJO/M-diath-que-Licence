Bonsoir Mr Haller

Voici le rendu du projet de la médiathèque en format MVC-PHP. 

Dans le dossier, j'ai exporté le fichier SQL contenant la BDD de la médiathèque 
Vous pourrez ainsi l'importer dans MySQL avant d'importer les Fixtures.

Voici ensuite les autres étapes pour l'installation.

# Projet de Gestion de Médias

Bienvenue dans le projet de gestion de médias ! Ce projet permet de gérer une collection de livres, de films et d’albums, incluant les fonctionnalités d'ajout, de recherche et de suppression.

## Prérequis

Avant de commencer, assurez-vous d'avoir les éléments suivants installés sur votre machine :

- **PHP** (version 8.2 ou supérieure)
- **MySQL** (ou MariaDB)
- **Apache** (ou tout autre serveur web compatible avec PHP)
- **XAMPP** ou **WAMP** si vous êtes sous Windows
- **Composer** si le projet nécessite des bibliothèques externes


### 1. Téléchargement du Projet depuis GitHub

1. Accédez au dépôt GitHub du projet et téléchargez le code source en tant qu'archive ZIP :
   - Cliquez sur **Code** > **Download ZIP**.
2. Extrayez l'archive dans le dossier web de votre serveur :
   - Pour XAMPP : Placez-le dans `C:/xampp/htdocs/`
   - Pour WAMP : Placez-le dans `C:/wamp/www/`

   Renommez le dossier extrait en `Mediathque` ou un autre nom significatif.

### 2. Création et Configuration de la Base de Données

1. **Créer une base de données MySQL :**
   - Connectez-vous à MySQL via phpMyAdmin ou la ligne de commande.
   - Créez une base de données nommée `mediatheque` (ou un nom de votre choix).

2. **Importer les données initiales :**
   - Le projet inclut un fichier SQL pour configurer les tables et données de base.

   - **Via phpMyAdmin** :
     - Sélectionnez la base de données `mediatheque.sql`.
     - Allez à l'onglet **Importer**  sur MySQL
     - Cliquez sur **Exécuter** pour importer les tables et données.

   - **Via la ligne de commande MySQL** :
     ```bash
     mysql -u votre_utilisateur -p mediatheque < fixtures/database.sql
     ```
     Remplacez `votre_utilisateur` par votre nom d'utilisateur MySQL.

### 3. Configurer les Paramètres de Connexion à la Base de Données

1. Ouvrez le fichier `db.php` à la racine de votre projet.
2. Mettez à jour les informations de connexion pour qu'elles correspondent à vos configurations MySQL :

   ```php
   <?php
   $host = 'localhost';
   $dbname = 'mediatheque'; // Remplacez par le nom de votre base de données si différent
   $username = 'votre_utilisateur';
   $password = 'votre_mot_de_passe';
   
   try {
       $connexion = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
       $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   } catch (PDOException $e) {
       echo "Erreur de connexion : " . $e->getMessage();
   }
   ?>
