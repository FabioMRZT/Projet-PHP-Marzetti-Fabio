<?php
require_once '../config/db_connect.php';
$conn = openDatabaseConnection();
$stmt = $conn->query("SELECT * FROM chambres ORDER BY disponibilite");
$chambres = $stmt->fetchAll(PDO::FETCH_ASSOC);
closeDatabaseConnection($conn);
if (isset($_GET['message'])) {
    $message = htmlspecialchars(urldecode($_GET['message']));
    
}


?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Chambres</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .btn-actions {
            white-space: nowrap;
        }

        .table-responsive {
            overflow-x: auto;
        }
    </style>
</head>

<body class="bg-light">
    <?php include '../asset/navbar.php'; ?>
    <?php include_once '../asset/gestionMessage.php'; ?>
    <div class="container mt-4 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">Liste des Chambres</h2>
            </div>
            <div class="card-body">
                <a href="createChambre.php" class="btn btn-success mb-3">
                    <i class="fas fa-plus"></i> Ajouter une chambre
                </a>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Disponibilité</th>
                                <th scope="col">Capacité</th>
                                <th scope="col">Numéro</th>
                                <th scope="col" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($chambres as $chambre): ?>
                                <tr>
                                    <td><?php echo $chambre['idChambre']; ?></td>
                                    <td><?php echo $chambre['disponibilite'] ?></td>
                                    <td><?php echo $chambre['capacite'] ?></td>
                                    <td><?php echo $chambre['numero'] ?></td>
                                    <td class="btn-actions">
                                        <a href="editChambre.php?idChambre=<?= $chambre['idChambre']; ?>"
                                            class="btn btn-sm btn-primary me-2">

                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                        <a href="deleteChambre.php?idChambre=<?= $chambre['idChambre']; ?>"
                                            onclick="return confirm('Êtes-vous sûr?')" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash-alt"></i> Supprimer
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Bienvenues !</strong> dans la Liste des chambres.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>



    <!-- Bootstrap Bundle avec Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/your-kit-code.js" crossorigin="anonymous"></script>
</body>

</html>