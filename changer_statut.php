<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit;
}

require_once 'connexion_bdd.php';

// Vérifier qu'on a un id valide dans l'URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: tableau_de_bord.php');
    exit;
}

$id = $_GET['id'];

// Récupérer le statut actuel de la consultation
$sql = "SELECT statut FROM consultations WHERE consultation_id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$consultation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$consultation) {
    header('Location: tableau_de_bord.php');
    exit;
}

$statut_actuel = $consultation['statut'];

// Déterminer le prochain statut
switch ($statut_actuel) {
    case 'en attente':
        $nouveau_statut = 'en cours';
        break;
    case 'en cours':
        $nouveau_statut = 'terminée';
        break;
    case 'terminée':
        // Déjà terminée, on ne fait rien ou on redirige
        header('Location: tableau_de_bord.php');
        exit;
    default:
        header('Location: tableau_de_bord.php');
        exit;
}

// Mettre à jour le statut
$sql = "UPDATE consultations SET statut = :statut WHERE consultation_id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':statut' => $nouveau_statut,
    ':id' => $id
]);

// Rediriger vers le tableau de bord
header('Location: tableau_de_bord.php');
exit;
?>