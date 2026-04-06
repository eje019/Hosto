<!-- 
 Afficher un formulaire (email + mot de passe)

Quand le formulaire est envoyé :
Chercher l'utilisateur par son email dans la base
Si l'email existe → vérifier le mot de passe avec password_verify()
Si tout est bon → démarrer une session et enregistrer l'utilisateur
Rediriger vers le tableau de bord
Si erreur → afficher un message -->


<?php
//demarrer la session (toujours en premier)
session_start();

//si utilisateur connecté, rediriger vers dashbaord
if (isset($_SESSION['utilisateur_id'])) {
    header("Location : tableau_de_bord.php");
    exit;
}

require_once 'connexion_bdd.php';

$erreur = '';

//traitement du formulaire
if($_SERVER["REQUEST_METHOD"] === "POST") {

    //on cherche l;utilisateur par son mail
    $sql = "SELECT * FROM utilisateurs WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":email" => $_POST["email"]]);
    $utilisateurs = $stmt->fetch(PDO::FETCH_ASSOC);

    //verifions si lutilisateur existe et si le mot de passe est correct 
    if ($utilisateur && password_verify($_POST['mot_de_passe'],$utilisateur['mot_de_passe'])) {
        //connexion reussie -> on enregistre en session
        $_SESSION['utilisateur_id'] = $utilisateur['utilisateur_id'];
        $_SESSION['utilisateur_nom'] = $utilisateur['nom'] . ' ' . $utilisateur['prenoms'];
        $_SESSION['utilisateur_email'] = $utilisateur['email'];    
        
        //on redirige vers le dashboard
        header('ocation: tableau_de_bord.php');
        exit;
    }
    else {
        $erreur = "Email ou mot de passe incorrect";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - HOSTO</title>
</head>
<body>
    <h1>Connectez vous</h1>
    <?php if ($erreur): ?>
        <p style="color: red;"><?= $erreur ?></p>
    <?php endif; ?>

    <form action="" method="POST">
        <input type="email" name="email" placeholder="Email required"><br><br>
        <input type="password" name="mot_de_passe" placeholder="Mot de passe" required><br><br>
        <button type="submit">Se connecter</button>
        <p>Pas de compte ? <a href="inscription php">S'inscrire</a></p>
    </form>
</body>
</html>