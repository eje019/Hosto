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
// TRAITEMENT DU FORMULAIRE
// ======================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Si "Aucun médecin" est sélectionné, on met NULL
    $medecin_id = !empty($_POST['medecin_id']) ? $_POST['medecin_id'] : null;
    
    $sql = "UPDATE consultations SET medecin_id = :medecin_id WHERE consultation_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':medecin_id' => $medecin_id,
        ':id' => $id
    ]);
    
    header('Location: tableau_de_bord.php');
    exit;
}

// ======================
// RÉCUPÉRATION DES DONNÉES POUR LE FORMULAIRE
// ======================

// Récupérer la consultation actuelle
$sql = "SELECT * FROM consultations WHERE consultation_id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$consultation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$consultation) {
    header('Location: tableau_de_bord.php');
    exit;
}

// Récupérer tous les médecins disponibles
$sqlMedecins = "SELECT * FROM medecins WHERE disponible = TRUE ORDER BY nom";
$stmtMedecins = $pdo->prepare($sqlMedecins);
$stmtMedecins->execute();
$medecins = $stmtMedecins->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Affecter un médecin - Clinique SANTE PLUS</title>
    <style>
        .form-group { margin-bottom: 15px; }
        label { display: inline-block; width: 150px; }
        .info { background-color: #f0f0f0; padding: 10px; margin-bottom: 20px; border-left: 4px solid #007bff; }
    </style>
</head>
<body>
    <h1>Affecter un médecin</h1>
    <p><a href="tableau_de_bord.php">← Retour au tableau de bord</a></p>
    
    <div class="info">
        <strong>Consultation :</strong> <?= htmlspecialchars($consultation['nom_patient']) ?> 
        (le <?= date('d/m/Y à H:i', strtotime($consultation['date_heure'])) ?>)
    </div>
    
    <form method="POST">
        <div class="form-group">
            <label>Médecin à affecter :</label>
            <select name="medecin_id">
                <option value="">-- Aucun médecin --</option>
                <?php foreach ($medecins as $medecin): ?>
                    <option value="<?= $medecin['medecin_id'] ?>"
                        <?= ($consultation['medecin_id'] == $medecin['medecin_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($medecin['prenoms'] . ' ' . $medecin['nom'] . ' - ' . $medecin['specialite']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label>&nbsp;</label>
            <button type="submit">Enregistrer</button>
            <a href="tableau_de_bord.php">Annuler</a>
        </div>
    </form>
</body>
</html>