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
        $nom = trim($_POST['nom']);

        $admin_user = trim($_POST['admin_user']);
        $admin_pass = md5(trim($_POST['admin_pass']));

        // Vérification des identifiants de l'administrateur courant
        $verifyAdmin = $bdd->prepare("SELECT COUNT(*) FROM admins WHERE usernamea = ? AND passa = ?");
        $verifyAdmin->execute([$admin_user, $admin_pass]);
        $isValidAdmin = $verifyAdmin->fetchColumn();

        if ($isValidAdmin == 0) {
            echo "<script>alert('Identifiants administrateur invalides.'); window.history.back();</script>";
            require_once __DIR__ . '/../includes/logger.php';
            writeLog("Suppression d'un service", "Échec d'authentification pour suppression par {$_SESSION['prenoma']} {$_SESSION['noma']}.");
            exit;
        }

        // Suppression
        $stmt = $bdd->prepare("DELETE FROM services WHERE serviced = ? ");
        if ($stmt->execute([$nom])) {
            echo "<script>alert('Service supprimé avec succès.'); window.location.href='tableServices.php';</script>";
            require_once __DIR__ . '/../includes/logger.php';
            writeLog("Suppression d'un service", "Service supprimé : {$nom} par {$_SESSION['prenoma']} {$_SESSION['noma']}.");
        } else {
            echo "<script>alert('Erreur lors de la suppression.'); window.history.back();</script>";
            require_once __DIR__ . '/../includes/logger.php';
            writeLog("Suppression d'un service", "Erreur suppression par {$_SESSION['prenoma']} {$_SESSION['noma']}.");
        }
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Supprimer un administrateur</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
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

    input[type=text], input[type=password] {
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

    .section-separator {
        margin: 30px 0 10px;
        border-top: 1px solid #ccc;
        text-align: center;
        position: relative;
    }

    .section-separator span {
        position: relative;
        top: -12px;
        background: #fff;
        padding: 0 10px;
        color: #666;
        font-size: 14px;
    }
  </style>
</head>
<body>

<div class="form-container">
    <a href="tableServices.php" class="w3-button w3-light-grey back-button"><i class="fa fa-arrow-left"></i> Retour à la liste</a>

    <h2><i class="fa fa-user-shield"></i> Supprimer un service</h2>

    <form method="post">
        <label for="nom">Nom du service :</label>
        <input type="text" name="nom" required>


        <div class="section-separator"><span>Confirmation administrateur</span></div>

        <label for="admin_user">Votre nom d'utilisateur :</label>
        <input type="text" name="admin_user" required>

        <label for="admin_pass">Votre mot de passe :</label>
        <input type="password" name="admin_pass" required>

        <input type="submit" value="Supprimer le service">
    </form>
</div>

</body>
</html>
