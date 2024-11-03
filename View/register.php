<!-- register.php -->

<h1>Page d'inscription</h1>
<form method="POST" action="../Config/reg.php">
    <label for="username">Nom d'utilisateur :</label>
    <input type="text" name="username" required>
    <label for="password">Mot de passe :</label>
    <input type="password" name="password">
    <button type="submit">S'inscrire</button>
</form>

<div class="actions">
    <a href="login.php" class="btn-add-media">Déjà inscrit ?</a>
</div>