<?php
    session_start();
    if (! isset($_SESSION['prenoma']) || ! isset($_SESSION['noma'])) {
        header("Location: index.php");
        exit;
    }

    $host = 'localhost';
    $user = 'ticket';
    $pass = 'btsinfo';
    $db   = 'SupportTickets';

    try {
        $bdd = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $nom    = trim($_POST['nom']);
        $prenom = trim($_POST['prenom']);
        $username = trim($_POST['username']);
        
        // Vérifie si l'utilisateur existe
        $stmt = $bdd->prepare("SELECT COUNT(*) FROM users WHERE nom = ? AND prenom = ? AND username = ? ");
        $stmt->execute([$nom, $prenom, $username]);
        $exists = $stmt->fetchColumn();

        if ($exists > 0) {
            // Supprime l'utilisateur
            $delete = $bdd->prepare("DELETE FROM users WHERE nom = ? AND prenom = ? AND username = ?");
            if ($delete->execute([$nom, $prenom, $username])) {
                echo "<script>alert('Utilisateur supprimé avec succès.'); window.location.href='tableUtilisateurs.php';</script>";
                require_once __DIR__ . '/../includes/logger.php';
                writeLog("Suppression utilisateur", "Utilisateur {$nom} {$prenom} supprimer par {$_SESSION['prenoma']} {$_SESSION['noma']}.");

            } else {
                echo "<script>alert('Erreur lors de la suppression.'); window.history.back();</script>";
                require_once __DIR__ . '/../includes/logger.php';
                writeLog("Suppression utilisateur", "Erreur lors de la suppression par {$_SESSION['prenoma']} {$_SESSION['noma']}.");
            }
        } else {
            echo "<script>alert('Aucun utilisateur trouvé avec ce nom et prénom.'); window.history.back();</script>";
            require_once __DIR__ . '/../includes/logger.php';
            writeLog("Suppression utilisateur", "Suppression échouée : utilisateur introuvable ({$nom} {$prenom}) - tentative de {$_SESSION['prenoma']} {$_SESSION['noma']}.");
        }
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Supprimer un utilisateur</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- W3.CSS -->
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
    body {
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8f9fa;
        padding: 30px;
    }

    .form-container {
        max-width: 600px;
        margin: auto;
        background: white;
        padding: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        border-radius: 10px;
    }

    input[type=text] {
        width: 100%;
        padding: 10px;
        margin: 8px 0 16px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    input[type=submit] {
        background-color: #343a40;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
    }

    input[type=submit]:hover {
        background-color: #e60000;
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    .back-button {
        margin-bottom: 20px;
        display: block;
        text-align: center;
    }
  </style>
</head>
<body>

<div class="form-container">
    <a href="tableUtilisateurs.php" class="w3-button w3-light-grey back-button"><i class="fa fa-arrow-left"></i> Retour à la liste</a>

    <h2><i class="fa fa-user-times"></i> Supprimer un utilisateur</h2>

    <form method="post">
        <label for="nom">Nom :</label>
        <input type="text" name="nom" required>

        <label for="prenom">Prénom :</label>
        <input type="text" name="prenom" required>

        <label for="username">Nom d'utilisateur :</label>
        <input type="text" name="username" required>

        <input type="submit" value="Supprimer l'utilisateur">
    </form>
</div>

</body>
</html>
