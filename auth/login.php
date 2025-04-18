<?php
require_once '../config/db_connect.php';
require_once 'authFunctions.php';

$error = '';

// Si déjà connecté, rediriger vers l'accueil
if (isLoggedIn()) {
    header("Location: /hotelFabio/index.php");
    exit;
}

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $conn = openDatabaseConnection();

    if (authenticateUser($username, $password, $conn)) {
        // Vérifier si le rôle est déjà défini dans la session
        error_log("ROLE " . $_SESSION['role']);
        if (!isset($_SESSION['role'])) {
            // Récupérer le rôle de l'utilisateur depuis la base de données
            $conn = openDatabaseConnection();
            $query = "SELECT role FROM employes WHERE username = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute([$username]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            error_log("Result = " . $result);
            if ($result) {
                // Ajouter le rôle à la session
                $_SESSION['role'] = $result['role'];
                //FIXME Role qui ne s'enregistre pas semble que $result ne contienne rien
                error_log("Role enregistré : " . $result['role']);
            } else {
                // Si aucun rôle n'est trouvé, définir un rôle par défaut
                $_SESSION['role'] = 'guest'; // Exemple : rôle par défaut
            }
        }
        $encodedMessage = urlencode("SUCCES : Bienvenue $username");
        header("Location: /hotelfabio/index.php?message=$encodedMessage");
        exit;
    } else {
        error_log("hotelfabio : authenticate_user = " . authenticateUser($username, $password, $conn));
        $encodedMessage = urlencode("ERREUR : Identifiants incorrects ($username)");
        header("Location: /hotelfabio/index.php?message=$encodedMessage");
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/assets/style.css">
    <style>
        /* Custom Styles */
        .login-container {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f0f2f5;
        }

        .login-card {
            max-width: 400px;
            padding: 30px;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .login-card h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .btn-login {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }

        .btn-login:hover {
            background-color: #218838;
            cursor: pointer;
        }

        .alert-custom {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <?php include '../asset/navbar.php'; ?>
    <div class="login-container">
        <div class="login-card">
            <h2>Connexion</h2>

            <?php if ($error): ?>
                <div class="alert alert-danger alert-custom" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="post" action="">
                <div class="form-group">
                    <label for="username">Identifiant employé:</label>
                    <input type="text" name="username" id="username" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe:</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-login">Se connecter</button>

                <div class="text-center mt-3">
                    <a href="/hotelFabio/forgot-password.php" class="text-muted">Mot de passe oublié ?</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>