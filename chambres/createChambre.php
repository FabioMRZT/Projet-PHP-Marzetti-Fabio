<?php
require_once '../config/db_connect.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = $_POST['numero'];
    $capacite = $_POST['capacite'];
    $disponibilite = $_POST['disponibilite'];
    if (empty($numero) || empty($capacite) || empty($disponibilite) || !is_numeric($numero) || !is_numeric($capacite) || !is_numeric($disponibilite)) {
        $encodedMessage = urlencode("ERREUR : une ou plusieurs valeurs erronnée(s).");
        header("Location: listChambres.php?message=$encodedMessage");
    } else {
        $conn = openDatabaseConnection();
        $stmt = $conn->prepare("INSERT INTO chambres (numero, capacite, disponibilite) VALUES (?, ?, ?)");
        $stmt->execute([$numero, $capacite, $disponibilite]);
        closeDatabaseConnection($conn);

        $encodedMessage = urlencode("SUCCES : ajout effectuée.");
        header("Location: listChambres.php?message=$encodedMessage");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Chambre</title>
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
                        <h1 class="mb-3">Ajouter une Chambre</h1>
                    </div>
                    <div class="card-body">
                        <form method="post" class="needs-validation" novalidate>
                            <div class="form-group">
                                <label for="disponibilite" class="form-label">Disponibilité</label>
                                <input type="text" id="disponibilite" name="disponibilite" class="form-control"
                                    required>
                                <div class="invalid-feedback">
                                    La disponibilité est requise
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="capacite" class="form-label">Capacité</label>
                                <input type="number" id="capacite" name="capacite" class="form-control" required
                                    min="1">
                                <div class="invalid-feedback">
                                    La capacité est requise et doit être supérieure à 0
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="numero" class="form-label">Numéro supérieur à 110</label>
                                <input type="number" id="numero" name="numero" class="form-control" required min="110">
                                <div class="invalid-feedback">
                                    le numéro doit etre superieure à 110
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