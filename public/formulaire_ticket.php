<?php
    session_start();
    header('Content-Type: text/html; charset=utf-8');

    if (! isset($_SESSION['prenom']) || ! isset($_SESSION['nom'])) {
        // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
        header("Location: index.php");
        exit;
    }
    $nom    = $_SESSION['nom'];
    $prenom = $_SESSION['prenom'];
    try {
        // variables globales
        $server = "localhost";
        $dbname = "SupportTickets";
        $user   = "ticket";
        $passwd = "btsinfo";
        // connexion
        $bdd = new PDO('mysql:host=' . $server . ';dbname=' . $dbname . ';charset=utf8',
            $user, $passwd);
    } catch (Exception $except) {
        // message si problème avec BDD
        die('Erreur:' . $except->getMessage());
    }
    $edition = 0;
    if (isset($_POST['idT'])) {
        $edition              = 1;
        $_SESSION['idticket'] = $_POST['idT'];
    }
    $_SESSION['edit'] = $edition;

?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <title>Mon espace demande</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Liens vers les feuilles de style -->
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <style>
    body,
    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
      font-family: "Raleway", sans-serif;
    }

    body,
    html {
      height: 100%;
      line-height: 1.8;
      margin: 0;
      padding: 0;
    }

    .bgimg-1 {
      background-position: center;
      background-size: cover;
      background-image: url("../img/fond-index.png");
      min-height: 100%;
    }

    .w3-bar .w3-button {
      padding: 16px;
    }

    .Logo {textarea
      width: 100px;
      height: 100px;
    }

    .form-container {
      background-color: rgba(255, 255, 255, 0.7);
      padding: 40px;
      margin: 0 20px;
      width: 100%;
      max-width: 700px;
      border-radius: 15px;
    }

    .form-container h3 {
      margin-top: 0;
    }

    .form-container label {
      display: block;
      margin-top: 15px;
      font-weight: bold;
    }

    .form-container input[type="text"],
    .form-container input[type="file"],
    .form-container select,
    .form-container textarea {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box;
    }

    .form-container input[type="submit"] {
      margin-top: 20px;
      background-color: #d50000;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }

    .form-container input[type="submit"]:hover {
      background-color: #b71c1c;
    }
  </style>
</head>

<body>

  <!-- Navbar -->
  <div class="w3-top">
    <div class="w3-bar w3-crimson w3-card" id="myNavbar">
      <span class="w3-bar-item w3-left" style="font-size: 20px">
        <i class="fa fa-life-ring"></i> Création du ticket : Bonjour,                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           <?php echo htmlspecialchars($_SESSION['nom'] . ' ' . $_SESSION['prenom']); ?> !
      </span>
      <div class="w3-right w3-hide-small" style="margin-top: 5px; margin-right: 5px;">
        <a href="logout.php" class="w3-bar-item w3-button w3-hover-teal">
          <i class="fa fa-sign-out"></i> Déconnexion
        </a>
        <a href="../index.html" class="w3-bar-item w3-button w3-hover-teal">
          <i class="fa fa-home"></i> Accueil
        </a>
        <a href="liste_tickets.php" class="w3-bar-item w3-button w3-hover-teal"><i class="fa fa-list"></i> Voir
          mes tickets</a>
      </div>
      <a href="javascript:void(0)" class="w3-bar-item w3-button w3-right w3-hide-large w3-hide-medium"
        onclick="w3_open()">
        <i class="fa fa-bars"></i>
      </a>
    </div>
  </div>

  <!-- Main -->
  <main class="bgimg-1 w3-display-container w3-grayscale-min" id="home"
    style="display: flex; justify-content: center; align-items: center; padding-top: 150px; padding-bottom: 50px;">
    <div class="form-container">
      <h3><i class="fa fa-ticket"></i> Créer un ticket d'aide</h3>

      <form method="post" action="traitement_ticket.php" enctype="multipart/form-data" class="w3-container w3-padding-16 w3-white w3-round-large">
        <!-- Type de demande -->
        <label for="type_demande"><strong>Type de demande :</strong></label>
        <select name="type_demande" id="type_demande" class="w3-select w3-border" required>
          <option value="" disabled selected>Choisissez votre type de problème</option>
          <?php
              $stmt = $bdd->prepare("SELECT * FROM type_demande");
              $stmt->execute();
              while ($donnees = $stmt->fetch()) {
                  if (($edition == 1) && ($_POST['type_demande2'] == $donnees["typed"])) {
                      echo "<option value=" . $donnees['id_demande'] . " selected >" . $donnees['typed'] . "</option>";
                  } else {
                      echo "<option value=" . $donnees['id_demande'] . ">" . $donnees['typed'] . "</option>";
                  }
              }
              $stmt->closeCursor();
          ?>
        </select>
        <br><br>

        <!-- Titre du ticket -->
        <label for="titre"><strong>Titre du ticket :</strong></label>
        <?php
            if ($edition == 1) {
                echo '<input type="text" id="titre" name="titre" class="w3-input w3-border" maxlength="255" value="' . $_POST['titre_2'] . '" required>';
            } else {
                echo '<input type="text" id="titre" name="titre" class="w3-input w3-border" maxlength="255" required>';
            }
        ?>
        <br><br>

        <!-- Description du problème -->
        <label for="description"><strong>Description détaillée :</strong></label>
        <?php
            if ($edition == 1) {
                echo '<input type="text" id="description" name="description" class="w3-input w3-border" rows="6" value="' . $_POST['description2'] . '"required></textarea>';
            } else {
                echo '<textarea id="description" name="description" class="w3-input w3-border" rows="6" required></textarea>';
            }
        ?>

        <br><br>

        <!-- Système d'exploitation -->
        <label for="systeme"><strong>Système d'exploitation :</strong></label>
        <select name="systeme" id="systeme" class="w3-select w3-border" required>
          <option value="" disabled selected>Choisissez votre OS</option>
          <?php
              $stmt = $bdd->prepare("SELECT * FROM systeme");
              $stmt->execute();
              while ($donnees = $stmt->fetch()) {
                  if (($edition == 1) && ($_POST['systeme2'] == $donnees["systemd"])) {
                      echo "<option value=" . $donnees['id_systeme'] . " selected >" . $donnees['systemd'] . "</option>";
                  } else {
                      echo "<option value=" . $donnees['id_systeme'] . ">" . $donnees['systemd'] . "</option>";
                  }
              }
              $stmt->closeCursor();
          ?>
        </select>
        <br><br>

        <!-- Service -->
        <label for="service"><strong>Votre service :</strong></label>
        <select name="service" id="service" class="w3-select w3-border" required>
          <option value="" disabled selected>Choisissez votre service</option>
          <?php
              $stmt = $bdd->prepare("SELECT * FROM services");
              $stmt->execute();
              while ($donnees = $stmt->fetch()) {
                  if (($edition == 1) && ($_POST['service2'] == $donnees["serviced"])) {
                      echo "<option value=" . $donnees['id_service'] . " selected >" . $donnees['serviced'] . "</option>";
                  } else {
                      echo "<option value=" . $donnees['id_service'] . ">" . $donnees['serviced'] . "</option>";
                  }
              }
              $stmt->closeCursor();
          ?>
        </select>
        <br><br>

        <!-- Soumission -->
        <button type="submit" class="w3-button w3-crimson w3-hover-teal w3-round-large ">
        <i class="fa fa-paper-plane" aria-hidden="true"></i> Envoyer le ticket
        </button>

        <?php
            require_once __DIR__ . '/../includes/logger.php';
            writeLog('Création ticket', "Ticket créer de {$nom} {$prenom}");
        ?>
      </form>
    </div>
  </main>

  <script>
    // Toggle sidebar menu
    var mySidebar = document.getElementById("mySidebar");

    function w3_open() {
      mySidebar.style.display = (mySidebar.style.display === 'block') ? 'none' : 'block';
    }

    function w3_close() {
      mySidebar.style.display = "none";
    }
  </script>

</body>

</html>
