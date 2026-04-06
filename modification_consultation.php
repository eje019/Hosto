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

// ======================
// TRAITEMENT DU FORMULAIRE (UPDATE)
// ======================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "UPDATE consultations 
            SET nom_patient = :nom_patient, 
                date_heure = :date_heure, 
                motif = :motif 
            WHERE consultation_id = :id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nom_patient' => $_POST['nom_patient'],
        ':date_heure' => $_POST['date_heure'],
        ':motif' => $_POST['motif'],
        ':id' => $id
    ]);
    
    // Rediriger vers le tableau de bord
    header('Location: tableau_de_bord.php');
    exit;
}

// ======================
// AFFICHAGE DU FORMULAIRE PRÉ-REMPLI
// ======================

// Récupérer la consultation à modifier
$sql = "SELECT * FROM consultations WHERE consultation_id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$consultation = $stmt->fetch(PDO::FETCH_ASSOC);

// Si l'id n'existe pas dans la base
if (!$consultation) {
    header('Location: tableau_de_bord.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Modifier consultation - Clinique SANTE PLUS</title>
    <style>
        .form-group { margin-bottom: 15px; }
        label { display: inline-block; width: 120px; }
    </style>
</head>
<body>
    <h1>Modifier la consultation</h1>
    <p><a href="tableau_de_bord.php">← Retour au tableau de bord</a></p>
    
    <form method="POST">
        <div class="form-group">
            <label>Nom du patient :</label>
            <input type="text" name="nom_patient" value="<?= htmlspecialchars($consultation['nom_patient']) ?>" required size="40">
        </div>
        
        <div class="form-group">
            <label>Date et heure :</label>
            <input type="datetime-local" name="date_heure" value="<?= date('Y-m-d\TH:i', strtotime($consultation['date_heure'])) ?>" required>
        </div>
        
        <div class="form-group">
            <label>Motif :</label>
            <textarea name="motif" rows="3" cols="40" required><?= htmlspecialchars($consultation['motif']) ?></textarea>
        </div>
        
        <div class="form-group">
            <label>&nbsp;</label>
            <button type="submit">Enregistrer les modifications</button>
            <a href="tableau_de_bord.php">Annuler</a>
        </div>
    </form>
</body>
</html>