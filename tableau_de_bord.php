<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connexion.php');
    exit;
}

require_once 'connexion_bdd.php';

// Récupérer toutes les consultations avec le nom du médecin
$sql = "SELECT consultations.*, 
               medecins.nom as medecin_nom, 
               medecins.prenoms as medecin_prenoms
        FROM consultations
        LEFT JOIN medecins ON consultations.medecin_id = medecins.medecin_id
        ORDER BY consultations.date_heure DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$consultations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord - HOSTO</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>Tableau de bord</h1>
    <p>Bonjour <strong><?= htmlspecialchars($_SESSION['utilisateur_nom']) ?></strong> | <a href="deconnexion.php">Déconnexion</a></p>
    
    <h2>Liste des consultations</h2>
    
    <?php if (empty($consultations)): ?>
        <p>Aucune consultation pour le moment.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Patient</th>
                    <th>Date et heure</th>
                    <th>Motif</th>
                    <th>Médecin</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($consultations as $consultation): ?>
                <tr>
                    <td><?= $consultation['consultation_id'] ?></td>
                    <td><?= htmlspecialchars($consultation['nom_patient']) ?></td>
                    <td><?= $consultation['date_heure'] ?></td>
                    <td><?= htmlspecialchars($consultation['motif']) ?></td>
                    <td>
                        <?php if ($consultation['medecin_id']): ?>
                            <?= htmlspecialchars($consultation['medecin_prenoms'] . ' ' . $consultation['medecin_nom']) ?>
                        <?php else: ?>
                            <em>Non affecté</em>
                        <?php endif; ?>
                    </td>
                    <td><?= $consultation['statut'] ?></td>
                    <td>
                        <a href="modifier_consultation.php?id=<?= $consultation['consultation_id'] ?>">Modifier</a>
                        | <a href="affecter_medecin.php?id=<?= $consultation['consultation_id'] ?>">Affecter médecin</a>
                        | <a href="changer_statut.php?id=<?= $consultation['consultation_id'] ?>">Changer statut</a>
                     </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    
    <br>
    <a href="ajouter_consultation.php">+ Nouvelle consultation</a>
</body>
</html>