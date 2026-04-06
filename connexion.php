<!-- 
 Afficher un formulaire (email + mot de passe)

Quand le formulaire est envoyé :
Chercher l'utilisateur par son email dans la base
Si l'email existe → vérifier le mot de passe avec password_verify()
Si tout est bon → démarrer une session et enregistrer l'utilisateur
Rediriger vers le tableau de bord
Si erreur → afficher un message -->


<<?php
// Démarrer la session (toujours en premier)
session_start();

// Si déjà connecté, rediriger vers le tableau de bord
if (isset($_SESSION['utilisateur_id'])) {
    header('Location: tableau_de_bord.php');
    exit;
}

require_once 'connexion_bdd.php';

$erreur = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Chercher l'utilisateur par son email
    $sql = "SELECT * FROM utilisateurs WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':email' => $_POST['email']]);
    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Vérifier si l'utilisateur existe ET si le mot de passe est correct
    if ($utilisateur && password_verify($_POST['mot_de_passe'], $utilisateur['mot_de_passe'])) {
        
        // Connexion réussie → on enregistre en session
        $_SESSION['utilisateur_id'] = $utilisateur['utilisateur_id'];
        $_SESSION['utilisateur_nom'] = $utilisateur['nom'] . ' ' . $utilisateur['prenoms'];
        $_SESSION['utilisateur_email'] = $utilisateur['email'];
        
        // Rediriger vers le tableau de bord
        header('Location: tableau_de_bord.php');
        exit;
    } else {
        $erreur = "Email ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Connexion - HOSTO</title>
</head>
<body>
    <h1>Se connecter</h1>
    
    <?php if ($erreur): ?>
        <p style="color: red;"><?= $erreur ?></p>
    <?php endif; ?>
    
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="password" name="mot_de_passe" placeholder="Mot de passe" required><br><br>
        <button type="submit">Se connecter</button>
    </form>
    
    <p>Pas de compte ? <a href="inscription.php">S'inscrire</a></p>
</body>
</html>