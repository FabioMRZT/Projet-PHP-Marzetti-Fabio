<?php
// Inclusion du fichier de connexion à la base de données
require_once '../config/db_connect.php';

// Méthode GET : on recherche la chambre demandée
$idChambre = isset($_GET['idChambre']) ? (int) $_GET['idChambre'] : 0;
// Vérifier si l'ID est valide
if ($idChambre <= 0) {
    echo $idChambre;
    //header("Location: listChambres.php");
    exit;
}

try {
    $conn = openDatabaseConnection();

    // Méthode GET : Récupérer les données de la chambre
    $stmt = $conn->prepare("SELECT * FROM chambres WHERE idChambre = ?");
    $stmt->execute([$idChambre]); // Utilisation correcte de $idChambre

    $chambres = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$chambres) {
        header("Location: listChambres.php");
        exit;
    }

    // Méthode POST : Traitement du formulaire si soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $numero = $_POST['numero'];
        $capacite = (int) $_POST['capacite'];

        // Validation des données
        $errors = [];
        if (empty($numero)) {
            $errors[] = "Le numéro de chambre est obligatoire.";
        }
        if ($capacite <= 0) {
            $errors[] = "La capacité doit être un nombre positif.";
        }

        // Si pas d'erreurs, mettre à jour les données
        if (empty($errors)) {
            $stmt = $conn->prepare("UPDATE chambres SET numero = ?, capacite = ? WHERE idChambre = ?");
            $stmt->execute([$numero, $capacite, $idChambre]); // Utilisation correcte de $idChambre

            header("Location: listChambres.php?success=1");
            exit;
        }
    }
} finally {
    closeDatabaseConnection($conn);
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Modifier une Chambre</title>
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
        <h1>Modifier une Chambre</h1>

        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?= $error ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label for="numero">Numéro de Chambre:</label>
                <input type="text" id="numero" name="numero" class="form-control"
                    value="<?= htmlspecialchars($chambres['numero']) ?>" required>
            </div>

            <div class="form-group">
                <label for="capacite">Capacité (nombre de personnes):</label>
                <input type="number" id="capacite" name="capacite" class="form-control"
                    value="<?= $chambres['capacite'] ?>" min="1" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                <a href="listChambres.php" class="btn btn-danger">Annuler</a>
            </div>
        </form>
    </div>

    <!-- Ajout de Bootstrap JS (et Popper.js pour les composants interactifs) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>