<?php
require_once '../config/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
    $nombrePersonnes = $_POST['nombrePersonnes'];

    $conn = openDatabaseConnection();
    $stmt = $conn->prepare("INSERT INTO clients (nom, telephone, email, nombrePersonnes) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nom, $telephone, $email, $nombrePersonnes]);
    closeDatabaseConnection($conn);

    header("Location: listClients.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Client</title>
    <!-- Intégration de Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <!-- Barre de navigation avec Bootstrap -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="../index.php">Accueil</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="listClients.php">Liste des Clients</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../chambres/listChambres.php">Chambres</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../reservations/listReservations.php">Réservations</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h1>Ajouter un Client</h1>
        <!-- Formulaire avec les champs nécessaires -->
        <form method="post">
            <div class="form-group">
                <label for="nom">Nom:</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="form-group">
                <label for="telephone">Téléphone:</label>
                <input type="tel" class="form-control" id="telephone" name="telephone" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="nombrePersonnes">Nombre de personnes:</label>
                <input type="number" class="form-control" id="nombrePersonnes" name="nombrePersonnes" required min="1">
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="listClients.php" class="btn btn-secondary">Retour à la liste</a>
        </form>
    </div>

    <!-- Ajout des fichiers JS de Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>