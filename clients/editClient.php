<?php
// Inclusion du fichier de connexion à la base de données
require_once '../config/db_connect.php';
$idClient = isset($_GET['id']) ? (int) $_GET['id'] : 0;
// Vérifier si l'ID est valide
if ($idClient <= 0) { // Correction de la variable ici
    echo $idClient;
    // header("Location: listClients.php");
    exit;
}
$conn = openDatabaseConnection();

// Traitement du formulaire si soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
    $nombrePersonnes = (int) $_POST['nombrePersonnes'];

    // Validation des données
    $errors = [];
    if (empty($nom)) {
        $errors[] = "Le nom est obligatoire.";
    }
    if (empty($telephone)) {
        $errors[] = "Le téléphone est obligatoire.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'email est invalide.";
    }
    if ($nombrePersonnes <= 0) {
        $errors[] = "Le nombre de personnes doit être un nombre positif.";
    }

    // Si pas d'erreurs, mettre à jour les données
    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE clients SET nom = ?, telephone = ?, email = ?, nombrePersonnes = ? WHERE idClient = ?");
        $stmt->execute([$nom, $telephone, $email, $nombrePersonnes, $idClient]);
        // Rediriger vers la liste des clients
        header("Location: listClients.php?success=1");
        exit;
    }
} else {
    // Récupérer les données du client
    $stmt = $conn->prepare("SELECT * FROM clients WHERE idClient = ?");
    $stmt->execute([$idClient]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);
    // Si le client n'existe pas, rediriger
    if (!$client) {
        header("Location: listClients.php");
        exit;
    }
}

closeDatabaseConnection($conn);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Client</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSS personnalisé -->
    <link href="../assets/style.css" rel="stylesheet">
</head>

<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.php">Accueil</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="listClients.php">Clients</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../chambres/listChambres.php">Chambres</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../reservations/listReservations.php">Réservations</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <div class="container">
        <!-- Messages d'erreur -->
        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <?php foreach ($errors as $error): ?>
                    <p class="mb-0"><?= $error ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Titre -->
        <h1 class="mb-4">Modifier un Client</h1>

        <!-- Formulaire -->
        <form method="post" class="bg-white p-4 rounded shadow-sm">
            <!-- Groupe Nom -->
            <div class="mb-3">
                <label for="nom" class="form-label">Nom:</label>
                <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($client['nom']) ?>"
                    class="form-control" required>
            </div>

            <!-- Groupe Téléphone -->
            <div class="mb-3">
                <label for="telephone" class="form-label">Téléphone:</label>
                <input type="tel" id="telephone" name="telephone" value="<?= htmlspecialchars($client['telephone']) ?>"
                    class="form-control" required>
            </div>

            <!-- Groupe Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($client['email']) ?>"
                    class="form-control" required>
            </div>

            <!-- Groupe Nombre de personnes -->
            <div class="mb-3">
                <label for="nombrePersonnes" class="form-label">Nombre de personnes:</label>
                <input type="number" id="nombrePersonnes" name="nombrePersonnes"
                    value="<?= $client['nombrePersonnes'] ?>" class="form-control" min="1" required>
            </div>

            <!-- Boutons d'action -->
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary px-4">Enregistrer les modifications</button>
                <a href="listClients.php" class="btn btn-secondary px-4">Annuler</a>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>