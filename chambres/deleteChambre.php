<?php
// Inclusion du fichier de connexion à la base de données
require_once '../config/db_connect.php';

$idChambre = isset($_GET['idChambre']) ? (int) $_GET['idChambre'] : 0;
// Vérifier si l'ID est valide
if ($idChambre <= 0) {
    header("Location: listChambres.php");
    exit;
}

$conn = openDatabaseConnection();
// Vérifier si la chambre existe
$stmt = $conn->prepare("SELECT * FROM chambres WHERE idChambre = ?");  // Correction : utiliser idChambre au lieu de id
$stmt->execute([$idChambre]);
$chambre = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$chambre) {
    header("Location: listChambres.php");
    exit;
}

// Vérifier si la chambre est utilisée dans des réservations
$stmt = $conn->prepare("SELECT COUNT(*) FROM reservations WHERE idChambre = ?");
$stmt->execute([$idChambre]);
$count = $stmt->fetchColumn();
$hasReservations = ($count > 0);

// Traitement de la suppression si confirmée
if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
    // Si la chambre a des réservations et que l'utilisateur souhaite les supprimer aussi
    if ($hasReservations && isset($_POST['delete_reservations']) && $_POST['delete_reservations'] === 'yes') {
        $stmt = $conn->prepare("DELETE FROM reservations WHERE idChambre = ?");
        $stmt->execute([$idChambre]);
    } elseif ($hasReservations) {
        // Si la chambre a des réservations mais l'utilisateur ne veut pas les supprimer
        header("Location: listChambres.php?error=1");
        exit;
    }

    // Supprimer la chambre
    $stmt = $conn->prepare("DELETE FROM chambres WHERE idChambre = ?");  // Correction : utiliser idChambre au lieu de id
    $stmt->execute([$idChambre]);
    // Rediriger vers la liste des chambres
    header("Location: listChambres.php?deleted=1");
    exit;
}

closeDatabaseConnection($conn);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Supprimer une Chambre</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Intégration de Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="../index.php">Accueil</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="listChambres.php">Chambres</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../clients/listClients.php">Clients</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../reservations/listReservations.php">Réservations</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="container mt-5">
        <h1>Supprimer une Chambre</h1>

        <!-- Alerte de confirmation -->
        <div class="alert alert-warning">
            <strong>Attention :</strong> Vous êtes sur le point de supprimer la chambre numéro
            <?= htmlspecialchars($chambre['numero']) ?>.
        </div>

        <?php if ($hasReservations): ?>
            <div class="alert alert-danger">
                <strong>Cette chambre est associée à <?= $count ?> réservation(s).</strong><br>
                La suppression de cette chambre affectera les réservations existantes.
            </div>
        <?php endif; ?>

        <form method="post">
            <?php if ($hasReservations): ?>
                <div class="form-check mb-3">
                    <input type="checkbox" id="delete_reservations" name="delete_reservations" value="yes"
                        class="form-check-input">
                    <label for="delete_reservations" class="form-check-label">Supprimer également les <?= $count ?>
                        réservation(s) associée(s) à cette chambre</label>
                </div>
            <?php endif; ?>

            <p>Êtes-vous sûr de vouloir supprimer cette chambre ?</p>

            <div class="form-group">
                <input type="hidden" name="confirm" value="yes">
                <button type="submit" class="btn btn-danger">Confirmer la suppression</button>
                <a href="listChambres.php" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>

    <!-- Ajout des fichiers JS de Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>