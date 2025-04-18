<?php
// Inclusion du fichier de connexion à la base de données
require_once '../config/db_connect.php';

// Méthode GET : on recherche la réservation demandée
$idReservation = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Vérifier si l'ID est valide
if ($idReservation <= 0) {
    echo $idReservation;
    exit;
}

try {
    $conn = openDatabaseConnection();

    // Méthode GET : Récupérer les données de la réservation et les informations associées
    $stmt = $conn->prepare("
        SELECT r.*, c.nom AS clientNom, ch.numero AS chambreNumero
        FROM reservations r
        JOIN clients c ON r.idClient = c.idClient
        JOIN chambres ch ON r.idChambre = ch.idChambre
        WHERE r.idReservation = ?
    ");
    $stmt->execute([$idReservation]);
    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$reservation) {
        header("Location: listReservations.php");
        exit;
    }

    // Méthode POST : Traitement du formulaire si soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $dateDebut = $_POST['dateDebut'];
        $dateFin = $_POST['dateFin'];
        $idChambre = (int) $_POST['idChambre'];
        $idClient = (int) $_POST['idClient'];

        // Validation des données
        $errors = [];

        // Validation des dates
        if (empty($dateDebut)) {
            $errors[] = "La date de début est obligatoire.";
        }
        if (empty($dateFin)) {
            $errors[] = "La date de fin est obligatoire.";
        }
        if ($dateDebut >= $dateFin) {
            $errors[] = "La date de début doit être antérieure à la date de fin.";
        }

        // Validation des IDs
        if ($idChambre <= 0) {
            $errors[] = "La chambre sélectionnée n'est pas valide.";
        }
        if ($idClient <= 0) {
            $errors[] = "Le client sélectionné n'est pas valide.";
        }

        // Si pas d'erreurs, mettre à jour les données
        if (empty($errors)) {
            $stmt = $conn->prepare("
                UPDATE reservations 
                SET dateDebut = ?, 
                    dateFin = ?, 
                    idChambre = ?, 
                    idClient = ?
                WHERE idReservation = ?
            ");
            $stmt->execute([
                $dateDebut,
                $dateFin,
                $idChambre,
                $idClient,
                $idReservation
            ]);

            header("Location: listReservations.php?success=1");
            exit;
        }
    }

    // Récupérer la liste des clients et chambres disponibles pour les dropdowns
    $stmt = $conn->prepare("SELECT idClient, nom, telephone, email, nombrePersonnes FROM clients ORDER BY nom, telephone");
    $stmt->execute();
    $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT idChambre, numero, capacite, disponibilite FROM chambres ORDER BY numero");
    $stmt->execute();
    $chambres = $stmt->fetchAll(PDO::FETCH_ASSOC);

} finally {
    closeDatabaseConnection($conn);
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Modifier une Réservation</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Ajout de Bootstrap -->
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
        <h1>Modifier une Réservation</h1>

        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?= $error ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label for="dateDebut">Date de début:</label>
                <input type="date" id="dateDebut" name="dateDebut" class="form-control"
                    value="<?= htmlspecialchars($reservation['dateDebut']) ?>" required>
            </div>

            <div class="form-group">
                <label for="dateFin">Date de fin:</label>
                <input type="date" id="dateFin" name="dateFin" class="form-control"
                    value="<?= htmlspecialchars($reservation['dateFin']) ?>" required>
            </div>

            <div class="form-group">
                <label for="idClient">Client:</label>
                <select id="idClient" name="idClient" class="form-control" required>
                    <?php foreach ($clients as $client): ?>
                        <option value="<?= $client['idClient'] ?>" <?= ($client['idClient'] == $reservation['idClient']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($client['nom'] . ' / ' . $client['telephone']. ' / '. $client['email'].' / '.$client['nombrePersonnes']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="idChambre">Chambre:</label>
                <select id="idChambre" name="idChambre" class="form-control" required>
                    <?php foreach ($chambres as $chambre): ?>
                        <option value="<?= $chambre['idChambre'] ?>" <?= ($chambre['idChambre'] == $reservation['idChambre']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars('Chambre ' . $chambre['numero'] . ' (' . $chambre['capacite'] . ' personnes)') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                <a href="listReservations.php" class="btn btn-danger">Annuler</a>
            </div>
        </form>
    </div>

    <!-- Ajout de Bootstrap JS (et Popper.js pour les composants interactifs) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>