<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=hosto", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e){
    die("Erreur de connexion a la base de donnees");
}
?>

<!-- 
new PDO : on ouvre une nouvelle connexion
dans cette nouvelle connexion on renseigne le nom du serveur : localhost , le nom de la base de donnees , le nom d'utilisateur sans mdp par defaut
on le met dans un try, catch pour afficher un messsage d'erreur clair si la connexion echoue 
-->
