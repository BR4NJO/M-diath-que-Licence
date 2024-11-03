<!-- login.php -->

<h1>Page de connexion</h1>
<form method="POST" action="../Config/log.php">
    <label for="username">Nom d'utilisateur :</label>
    <input type="text" name="username" required>
    <label for="password">Mot de passe :</label>
    <input type="password" name="password" required>
    <button type="submit">Se connecter</button>
</form>


<div class="actions">
    <a href="register.php" class="btn-add-media">Pas encore inscrit ?</a>
</div>
