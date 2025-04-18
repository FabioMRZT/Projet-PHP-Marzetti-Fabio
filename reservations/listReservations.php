<?php
// Inclusion du fichier de connexion à la base de données
require_once '../config/db_connect.php';

// Fonction pour formater les dates
function formatDate($date)
{
    $timestamp = strtotime($date);
    return date('d/m/Y', $timestamp);
}

try {
    // Connexion à la base de données
    $conn = openDatabaseConnection();

    // Requête SQL pour récupérer les réservations avec les informations des clients et des chambres
    $query = "SELECT r.idReservation, r.dateDebut, r.dateFin,
                     c.nom AS client_nom, c.telephone AS client_telephone, c.email AS client_email, c.nombrePersonnes AS client_nombrePersonnes,
                     ch.numero AS chambre_numero, ch.capacite AS chambre_capacite, ch.disponibilite AS chambre_disponibilite
              FROM reservations r
              JOIN clients c ON r.idClient = c.idClient
              JOIN chambres ch ON r.idChambre = ch.idChambre
              ORDER BY r.dateDebut DESC";

    // Préparer et exécuter la requête SQL
    $stmt = $conn->prepare($query);
    $stmt->execute();

    // Récupérer les résultats
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erreur SQL : " . $e->getMessage();
    exit;
} finally {
    closeDatabaseConnection($conn);
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Réservations</title>

    <!-- Inclure Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Optionnel : Vous pouvez ajouter une feuille de style personnalisée -->
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>
    <?php include '../asset/navbar.php'; ?>
    <?php include_once '../asset/gestionMessage.php'; ?>
    <div class="container mt-4">
        <h1 class="mb-4">Liste des Réservations</h1>

        <div class="actions mb-3">
            <a href="createReservation.php" class="btn btn-success">Nouvelle Réservation</a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Contact</th>
                        <th>Chambre</th>
                        <th>Arrivée</th>
                        <th>Départ</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($reservations) > 0): ?>
                        <?php foreach ($reservations as $reservation): ?>
                            <?php
                            $aujourd_hui = date('Y-m-d');
                            $statut = '';

                            if ($reservation['dateDebut'] < $aujourd_hui) {
                                $statut_class = 'text-muted';
                                $statut = 'Terminée';
                            } elseif (
                                $reservation['dateDebut'] <= $aujourd_hui &&
                                $reservation['dateFin'] >= $aujourd_hui
                            ) {
                                $statut_class = 'text-primary';
                                $statut = 'En cours';
                            } else {
                                $statut_class = 'text-success';
                                $statut = 'À venir';
                            }
                            ?>
                            <tr>
                                <td><?= $reservation['idReservation'] ?></td>
                                <td><?= htmlspecialchars($reservation['client_nom']) ?></td>
                                <td>
                                    <strong>Tél:</strong>
                                    <?= htmlspecialchars($reservation['client_telephone']) ?><br>
                                    <strong>Email:</strong>
                                    <?= htmlspecialchars($reservation['client_email']) ?><br>
                                    <strong>Nombre de personnes:</strong>
                                    <?= htmlspecialchars($reservation['client_nombrePersonnes']) ?>
                                </td>
                                <td>
                                    N° <?= htmlspecialchars($reservation['chambre_numero']) ?>
                                    (<?= $reservation['chambre_capacite'] ?> pers.)
                                </td>
                                <td><?= formatDate($reservation['dateDebut']) ?></td>
                                <td><?= formatDate($reservation['dateFin']) ?></td>
                                <td class="<?= $statut_class ?>"><?= $statut ?></td>
                                <td>
                                    <a href="editReservation.php?id=<?= $reservation['idReservation'] ?>"
                                        class="btn btn-primary btn-sm">Modifier</a>
                                    <a href="deleteReservation.php?id=<?= $reservation['idReservation'] ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réservation?');">
                                        Supprimer
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">Aucune réservation trouvée.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Bienvenues !</strong> dans la Liste des résérvations.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <!-- Inclure Bootstrap JS (optionnel) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>