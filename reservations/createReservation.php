<?php
require_once '../config/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $idClient = $_POST['idClient'];
    $idChambre = $_POST['idChambre'];
    $dateDebut = $_POST['dateDebut'];
    $dateFin = $_POST['dateFin'];

    // Validation des données
    if (empty($idClient) || empty($idChambre) || empty($dateDebut) || empty($dateFin)) {
        $encodedMessage = urlencode("ERREUR : une ou plusieurs valeurs manquantes.");
        header("Location: listReservations.php?message=$encodedMessage");
        exit;
    }

    // Connexion à la base de données
    $conn = openDatabaseConnection();

    // Préparation et exécution de l'insertion dans la table reservations
    $stmt = $conn->prepare("INSERT INTO reservations (idClient, idChambre, dateDebut, dateFin) VALUES (?, ?, ?, ?)");
    $stmt->execute([$idClient, $idChambre, $dateDebut, $dateFin]);

    // Fermer la connexion
    closeDatabaseConnection($conn);

    // Message de succès
    $encodedMessage = urlencode("SUCCES : Réservation effectuée.");
    header("Location: listReservations.php?message=$encodedMessage");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Réservation</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .form-container {
            transition: transform 0.3s ease;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .form-container:hover {
            transform: translateY(-5px);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-control {
            border-radius: 8px;
            padding: 0.75rem;
        }

        .btn-primary {
            background-color: #0d6efd;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .back-link {
            display: inline-block;
            margin-top: 1rem;
            color: #0d6efd;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: #0b5ed7;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card form-container shadow-lg">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <h1 class="mb-3">Ajouter une Réservation</h1>
                    </div>
                    <div class="card-body">
                        <form method="post" class="needs-validation" novalidate>
                            <div class="form-group">
                                <label for="idClient" class="form-label">Client</label>
                                <select id="idClient" name="idClient" class="form-control" required>
                                    <option value="">Sélectionner un client</option>
                                    <?php
                                    // Connexion à la base de données et récupérer les clients
                                    $conn = openDatabaseConnection();
                                    $stmt = $conn->query("SELECT idClient, nom, nombrePersonnes FROM clients");
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<option value='{$row['idClient']}'>{$row['nom']} nombre de personnes : {$row['nombrePersonnes']}</option>";
                                    }
                                    closeDatabaseConnection($conn);
                                    ?>
                                </select>
                                <div class="invalid-feedback">
                                    Le client est requis
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="idChambre" class="form-label">Chambre</label>
                                <select id="idChambre" name="idChambre" class="form-control" required>
                                    <option value="">Sélectionner une chambre</option>
                                    <?php
                                    // Connexion à la base de données et récupérer les chambres disponibles
                                    $conn = openDatabaseConnection();
                                    try {
                                        $stmt = $conn->prepare("SELECT idChambre, numero, capacite FROM chambres WHERE disponibilite = 1");
                                        $stmt->execute();

                                        if ($stmt->rowCount() > 0) {
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<option value=' {$row['idChambre']}'>{$row['numero']}  capacite : {$row['capacite']}</option>";
                                            }
                                        } else {
                                            echo "<option value=''>Aucune chambre disponible</option>";
                                        }
                                    } catch (PDOException $e) {
                                        // Gestion des erreurs SQL
                                        echo "Erreur SQL : " . $e->getMessage();
                                    }
                                    closeDatabaseConnection($conn);
                                    ?>
                                </select>
                                <div class="invalid-feedback">
                                    La chambre est requise
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="dateDebut" class="form-label">Date de début</label>
                                <input type="date" id="dateDebut" name="dateDebut" class="form-control" required>
                                <div class="invalid-feedback">
                                    La date de début est requise
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="dateFin" class="form-label">Date de fin</label>
                                <input type="date" id="dateFin" name="dateFin" class="form-control" required>
                                <div class="invalid-feedback">
                                    La date de fin est requise
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                Enregistrer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle avec Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Activation de la validation HTML5
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
</body>

</html>