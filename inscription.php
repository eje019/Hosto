<!-- 
Afficher un formulaire (nom, prénoms, email, mot de passe)
Quand le formulaire est envoyé :
Vérifier que l'email n'est pas déjà utilisé
Hacher le mot de passe
Insérer dans la table utilisateurs
Rediriger vers la page de connexion 
-->

<?php
//le fichier de connexion a la base de donnees doit etre requis sur tous les fichiers qui interagissent directement avec elle
require_once "connexion_bdd.php";

//traitement du formulaire
if($_SERVER["REQUEST_METHOD"] === 'POST'){

    //verfifier si email existant
    $sql = "SELECT * FROM utilisateurs WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":email" => $_POST["email"]]);

    if($stmt->fetch()){
        $erreur = "Cet email est deja utilisé";
    } else {
        $mot_de_passe_hache = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
    }

    //insertion du nouvel utilisateur
    $sql = "INSERT INTO utilisateurs (nom, prenoms, email, mot_de_passe)
            VALUES (:nom, :prenoms, :email, :mot_de_passe)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nom' => $_POST['nom'],
        ':prenoms' => $_POST['prenoms'],
        ':email' => $_POST['email'],
        'mot_de_passe' => $mot_de_passe_hache
    ]);

    //rediriger vers la connexion
    header('Location: connexion.php');
    exit;
    //toujours mettre un exit apres avoir utilise un header
}
?>

<!--formulaire html-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - HOSTO</title>
</head>
<body>
    <h1>Creer un compte</h1>
    <?php
        if(isset($erreur)): 
    ?>
    <p style="color: red"><?= $erreur ?></p>
    <?php endif ?>
    <form action="" method="POST">
        <input type="text" name="nom" placeholder="Nom" required><br><br>
        <input type="text" name="prenoms" placeholder="Prénoms" required><br><br>
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="password" name="mot_de_passe" placeholder="Mot de passe" required><br><br>
        <button type="submit">S'inscrire</button>    
    </form>
    <p>Deja un compte ? <a href="connexion.php">Se connecter</a></p>
</body>
</html>