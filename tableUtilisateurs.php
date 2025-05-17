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
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Utilisateurs</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- W3.CSS -->
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
  <!-- Google Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    body {
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8f9fa;
    }

    h1 {
        text-align: center;
        color: #343a40;
    }

    form.inline-form, form[method="GET"] {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 20px;
        justify-content: center;
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        background: white;
        padding: 20px;
    }

    th {
        background: #343a40;
        color: white;
        padding: 12px;
        text-align: center;
    }

    td {
        padding: 10px;
        border-bottom: 1px solid #dee2e6;
        text-align: center;
    }

    tr:hover {
        background-color: #f1f1f1;
    }

    .saisie_nom{
      margin-left: 20px;
      margin-bottom: 20px;
    }
</style>
</head>
<body>

  <!-- Navbar -->
  <div class="w3-top">
    <div class="w3-bar w3-card" style="background-color: #343a40; color:white">
      <span class="w3-bar-item w3-left" style="font-size: 20px">
        <i class="fa fa-life-ring"></i> Bonjour                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          <?php echo htmlspecialchars($_SESSION['prenoma']); ?>!
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
<br><br><br>
<h1>La liste des utilisateurs :</h1><br>

<div style="text-align: right; margin-bottom: 20px; padding: 20px;">
    <a href="ajoutUtilisateur.php" class="w3-button w3-green w3-round-large">
        <i class="fa fa-user-plus"></i> Ajouter un utilisateur
    </a>
    <a href="supprimerUtilisateur.php" class="w3-button w3-green w3-round-large">
        <i class="fa fa-minus"></i> Supprimer un utilisateur
    </a>
</div>

<form method="POST" class="saisie_nom">
  <input type="text" name="nom" placeholder="Nom de l'utilisateur :" required>
  <button type="submit" class="w3-button w3-gray w3-hover-green">Rechercher</button>
  <a href="tableUtilisateurs.php" class="w3-button w3-gray w3-hover-green">Réinitialiser</a>
</form>

<table>
<?php
    if (isset($_POST["nom"]) && ! empty(trim($_POST["nom"]))) {
        $stmt2 = $bdd->prepare("SELECT * FROM users WHERE nom = :nom");
        $stmt2->execute([':nom' => $_POST['nom']]);
        $users = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $stmt = $bdd->prepare("SELECT * FROM users");
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    echo '<table>
<tr>
  <th>ID</th>
  <th>Nom</th>
  <th>Prénom</th>
  <th>Mail</th>
  <th>UserName</th>
  <th>Password</th>
</tr>';

    foreach ($users as $user) {
        echo "<tr style='background-color:rgb(214, 214, 214);'>";
        echo '<td>' . htmlspecialchars($user['id']) . '</td>';
        echo '<td>' . htmlspecialchars($user['nom']) . '</td>';
        echo '<td>' . htmlspecialchars($user['prenom']) . '</td>';
        echo '<td>' . htmlspecialchars($user['mail']) . '</td>';
        echo '<td>' . htmlspecialchars($user['username']) . '</td>';
        echo '<td>' . htmlspecialchars($user['pass']) . '</td>';
        echo "</tr>";
    }
    echo '</table>';

?>
</table>
</body>
