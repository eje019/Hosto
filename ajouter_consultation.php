<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit;
}

require_once 'connexion_bdd.php';

// Récupérer tous les médecins pour la liste déroulante
$sqlMedecins = "SELECT * FROM medecins WHERE disponible = TRUE ORDER BY nom";
$stmtMedecins = $pdo->prepare($sqlMedecins);
$stmtMedecins->execute();
$medecins = $stmtMedecins->fetchAll(PDO::FETCH_ASSOC);

$erreur = '';
$succes = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Validation simple
    if (empty($_POST['nom_patient']) || empty($_POST['date_heure']) || empty($_POST['motif'])) {
        $erreur = "Veuillez remplir tous les champs obligatoires.";
    } else {
        // Insertion de la consultation
        $sql = "INSERT INTO consultations (nom_patient, date_heure, motif, medecin_id, statut) 
                VALUES (:nom_patient, :date_heure, :motif, :medecin_id, 'en attente')";
        
        $stmt = $pdo->prepare($sql);
        
        // Si aucun médecin sélectionné, on met NULL
        $medecin_id = !empty($_POST['medecin_id']) ? $_POST['medecin_id'] : null;
        
        $stmt->execute([
            ':nom_patient' => $_POST['nom_patient'],
            ':date_heure' => $_POST['date_heure'],
            ':motif' => $_POST['motif'],
            ':medecin_id' => $medecin_id
        ]);
        
        $succes = "Consultation ajoutée avec succès !";
        
        // Redirection après 2 secondes (pour voir le message)
        header("refresh:2;url=tableau_de_bord.php");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Nouvelle consultation - Clinique SANTE PLUS</title>
    <style>
        .form-group { margin-bottom: 15px; }
        label { display: inline-block; width: 120px; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <h1>Nouvelle consultation</h1>
    <p><a href="tableau_de_bord.php">← Retour au tableau de bord</a></p>
    
    <?php if ($erreur): ?>
        <p class="error"><?= $erreur ?></p>
    <?php endif; ?>
    
    <?php if ($succes): ?>
        <p class="success"><?= $succes ?> Redirection en cours...</p>
    <?php endif; ?>
    
    <?php if (!$succes): ?>
    <form method="POST">
        <div class="form-group">
            <label>Nom du patient * :</label>
            <input type="text" name="nom_patient" required size="40">
        </div>
        
        <div class="form-group">
            <label>Date et heure * :</label>
            <input type="datetime-local" name="date_heure" required>
        </div>
        
        <div class="form-group">
            <label>Motif * :</label>
            <textarea name="motif" rows="3" cols="40" required></textarea>
        </div>
        
        <div class="form-group">
            <label>Médecin :</label>
            <select name="medecin_id">
                <option value="">-- Aucun médecin --</option>
                <?php foreach ($medecins as $medecin): ?>
                    <option value="<?= $medecin['medecin_id'] ?>">
                        <?= htmlspecialchars($medecin['prenoms'] . ' ' . $medecin['nom'] . ' - ' . $medecin['specialite']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label>&nbsp;</label>
            <button type="submit">Enregistrer la consultation</button>
        </div>
    </form>
    <?php endif; ?>
</body>
</html>