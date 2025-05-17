<?php
    session_start();
    if (! isset($_SESSION['prenoma']) || ! isset($_SESSION['noma'])) {
        header("Location: index.php");
        exit;
    }

                                                                  // Récupérer la date soumise (format: YYYY-MM-DD)
    $date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d'); // Par défaut, aujourd'hui

    $logFile = "../logs/helpdesk_$date.log";
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Panel</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    table {
        border-collapse: collapse;
        width: 100%;
        font-family: monospace;
        background: #fff;
    }
    th, td {
        border: 1px solid #ccc;
        padding: 8px;
    }
    th {
        background-color: #333;
        color: #fff;
    }
    tr:nth-child(even) {
        background-color: #f2f2f2;
    }
</style>
</head>
<body>

<div class="w3-top">
    <div class="w3-bar w3-card" style="background-color: #343a40; color:white">
      <span class="w3-bar-item w3-left" style="font-size: 20px">
        <i class="fa fa-life-ring"></i> Bonjour                                                                                                                                                                                                                                                                                                                                                                                         <?php echo htmlspecialchars($_SESSION['prenoma']); ?>!
      </span>
      <div class="w3-right w3-hide-small" style="margin-top: 5px; margin-right: 5px;">
        <a href="adminPanel.php" class="w3-bar-item w3-button w3-hover-teal">
          <i class="fa fa-home"></i> Accueil
        </a>
        <a href="logout.php" class="w3-bar-item w3-button w3-hover-teal">
          <i class="fa fa-sign-out"></i> Déconnexion
        </a>
      </div>
    </div>
  </div>

  <br><br>

<h2>Journal des logs du <?php echo htmlspecialchars($date)?> :</h2>

<h4>
<form method="get" style="margin-bottom: 20px;">
    <label for="date">Choisir une date :</label>
    <input type="date" name="date" id="date" value="<?php echo htmlspecialchars($date)?>">
    <input type="submit" value="Afficher les logs">
</form>
</h4>

<?php
    if (! file_exists($logFile)) {
        echo "<p style='color: red;'>Fichier log introuvable pour le $date.</p>";
        return;
    }

    $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
?>
<table>
<tr><th>Date</th><th>IP</th><th>Utilisateur</th><th>Niveau</th><th>Action</th><th>Message</th></tr>
<?php
    foreach ($lines as $line) {
        if (preg_match('/^\[(.*?)\]\[(.*?)\]\[(.*?)\]\[(.*?)\]\[(.*?)\] (.*)$/', $line, $matches)) {
            list(, $logDate, $ip, $user, $niveau, $action, $message) = $matches;
            echo "<tr>
                <td>$logDate</td>
                <td>$ip</td>
                <td>$user</td>
                <td>$niveau</td>
                <td>$action</td>
                <td>$message</td>
              </tr>";
        }
    }
?>
</table>
</body>
