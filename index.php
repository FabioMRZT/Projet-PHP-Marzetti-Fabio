<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Système de Gestion d'Hôtel</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-image: url('asset/image.png');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: white;
            overflow: hidden;
        }

        .dashboard-card {
            background: rgba(0, 0, 0, 0.7);
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .dashboard-card:hover {
            transform: translateY(-10px);
        }

        .card-header {
            border-radius: 20px 20px 0 0;
        }

        .nav-link {
            color: white !important;
            transition: all 0.3s ease;
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .nav-link:hover {
            opacity: 0.8;
            transform: scale(1.05);
        }

        .hotel-icon {
            width: 90px;
            height: 90px;
            margin-bottom: 1rem;
            transition: transform 0.3s ease;
        }

        .hotel-icon:hover {
            transform: rotate(360deg);
        }

        .badge {
            font-size: 1.1rem;
            text-transform: uppercase;
            font-weight: bold;
            background-color: white;
            color: #333;
        }

        .card-body {
            padding: 30px;
        }

        h1 {
            font-size: 2.5rem;
            letter-spacing: 2px;
            font-weight: 700;
        }

        /* Nouveau style pour les liens de connexion et déconnexion */
        .nav-link-login {
            background-color: #007bff;
            /* Couleur bleue */
        }

        .nav-link-login:hover {
            background-color: #0056b3;
            /* Couleur plus foncée */
        }

        .nav-link-logout {
            background-color: #dc3545;
            /* Couleur rouge pour la déconnexion */
        }

        .nav-link-logout:hover {
            background-color: #c82333;
            /* Couleur plus foncée */
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card dashboard-card shadow-lg">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <h1 class="mb-3">Système de Gestion d'Hôtel</h1>
                        <div class="text-center">
                            <i class="fas fa-hotel hotel-icon"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <nav class="nav flex-column gap-3">
                            <a href="chambres/listChambres.php"
                                class="nav-link bg-primary fw-bold align-items-center gap-2">
                               
                                <i class="fas fa-bed me-2"></i>
                                Gestion des Chambres
                            </a>
                            <a href="clients/listClients.php"
                                class="nav-link bg-secondary fw-bold align-items-center gap-2">
                                
                                <i class="fas fa-users me-2"></i>
                                Gestion des Clients
                            </a>
                            <a href="reservations/listReservations.php"
                                class="nav-link bg-success fw-bold align-items-center gap-2">
                                <i class="fas fa-calendar-check me-2"></i>
                                Gestion des Réservations
                            </a>
                            <!-- Connexion -->
                            <a href="auth/login.php" class="nav-link nav-link-login fw-bold align-items-center gap-2">
                                
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Connexion
                            </a>
                            <!-- Déconnexion -->
                            <a href="auth/logout.php" class="nav-link nav-link-logout fw-bold align-items-center gap-2">
                               
                                <i class="fas fa-sign-out-alt me-2"></i>
                                Déconnexion
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle avec Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>