<?php
// Inclusion du fichier de connexion à la base de données
require_once '../config/db_connect.php';

// Récupération et validation de l'ID de réservation
$idReservation = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Vérification si l'ID est valide
if ($idReservation <= 0) {
    header("Location: listReservations.php");
    exit;
}

// Connexion à la base de données
$conn = openDatabaseConnection();

// Vérification si la réservation existe
$stmt = $conn->prepare("SELECT r.*, c.nom AS nom_client, ch.numero AS numero_chambre 
                       FROM reservations r 
                       INNER JOIN clients c ON r.idClient = c.idClient
                       INNER JOIN chambres ch ON r.idChambre = ch.idChambre
                       WHERE r.idReservation = ?");
$stmt->execute([$idReservation]);
$reservation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reservation) {
    header("Location: listReservations.php");
    exit;
}

// Traitement de la suppression si confirmée
if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
    // Suppression de la réservation
    $stmt = $conn->prepare("DELETE FROM reservations WHERE idReservation = ?");
    $stmt->execute([$idReservation]);

    // Redirection vers la liste des réservations
    header("Location: listReservations.php?deleted=1");
    exit;
}

closeDatabaseConnection($conn);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Supprimer une Réservation</title>
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
                    <a class="nav-link" href="../chambres/listChambres.php">Chambres</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../clients/listClients.php">Clients</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="listReservations.php">Réservations</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="container mt-5">
        <h1>Supprimer une Réservation</h1>

        <!-- Affichage des détails de la réservation -->
        <div class="card mb-4">
            <div class="card-body">
                <h5>Détails de la réservation</h5>
                <p><strong>Client :</strong> <?= htmlspecialchars($reservation['nom_client']) ?></p>
                <p><strong>Chambre :</strong> <?= htmlspecialchars($reservation['numero_chambre']) ?></p>
                <p><strong>Date de début :</strong>
                    <?= htmlspecialchars(date('d/m/Y', strtotime($reservation['dateDebut']))) ?></p>
                <p><strong>Date de fin :</strong>
                    <?= htmlspecialchars(date('d/m/Y', strtotime($reservation['dateFin']))) ?></p>
            </div>
        </div>

        <!-- Formulaire de confirmation -->
        <form method="post">
            <p>Êtes-vous sûr de vouloir supprimer cette réservation ?</p>
            <div class="form-group">
                <input type="hidden" name="confirm" value="yes">
                <button type="submit" class="btn btn-danger">Confirmer la suppression</button>
                <a href="listReservations.php" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>

    <!-- Ajout des fichiers JS de Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>