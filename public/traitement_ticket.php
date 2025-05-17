<?php
    session_start();

    // Vérifier si l'utilisateur est connecté
    if (! isset($_SESSION['prenom']) || ! isset($_SESSION['nom'])) {
        header("Location: index.php");
        exit;
    }

    // Connexion à la base de données
    $host     = 'localhost';
    $dbname   = 'SupportTickets';
    $username = 'ticket';
    $password = 'btsinfo'; // Remplacez par votre mot de passe si nécessaire

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        // Activer les exceptions PDO
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }

    // Récupérer les données du formulaire
    $type_demande = $_POST['type_demande'] ?? '';
    $titre        = $_POST['titre'] ?? '';
    $description  = $_POST['description'] ?? '';
    $systeme      = $_POST['systeme'] ?? '';
    $service      = $_POST['service'] ?? '';
    $idt          = $_SESSION['idticket'] ?? '';

    // Préparer et exécuter la requête d'insertion
    if ($_SESSION['edit'] == 0) {
        $sql = "INSERT INTO tickets
        (nom, prenom, id_demande, titre, description, id_systeme, id_service)
        VALUES
        (:nom, :prenom, :id_demande, :titre, :description, :id_systeme, :id_service)";
    } else {
        $sql = "UPDATE tickets
      SET id_demande = :id_demande, titre = :titre, description = :description, id_systeme = :id_systeme, id_service = :id_service
      WHERE id = :id_ticket";
    }

    if ($_SESSION['edit'] == 0) {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nom'         => $_SESSION['nom'],
            ':prenom'      => $_SESSION['prenom'],
            ':id_demande'  => $type_demande,
            ':titre'       => $titre,
            ':description' => $description,
            ':id_systeme'  => $systeme,
            ':id_service'  => $service,
        ]);
    } else {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id_demande'  => $type_demande,
            ':titre'       => $titre,
            ':description' => $description,
            ':id_systeme'  => $systeme,
            ':id_service'  => $service,
            ':id_ticket'   => $idt,
        ]);
    }

?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <title>Ticket créé</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- W3.CSS -->
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
  <!-- Google Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
    body, h1, h2, h3, h4, h5, h6 {
      font-family: "Raleway", sans-serif;
    }
    body, html {
      margin: 0; padding: 0;
      height: 100%; line-height: 1.8;
    }
    .bgimg-1 {
      background-position: center;
      background-size: cover;
      background-image: url("../img/fond-index.png");
      min-height: 100%;
    }
    .msg-container {
      background-color: white;
      padding: 40px;
      margin: 0 20px;
      max-width: 600px;
      border-radius: 15px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      text-align: center;
    }
    .msg-container h2 {
      color: #4CAF50;
      margin-bottom: 20px;
    }
    .btn {
      margin-top: 20px;
      display: inline-block;
      padding: 12px 24px;
      background-color: #d50000;
      color: white;
      text-decoration: none;
      border-radius: 5px;
    }
    .btn:hover {
      background-color: #b71c1c;
    }
    .w3-bar .w3-button {
      padding: 16px;
    }
  </style>
</head>

<body>

  <!-- Navbar -->
  <div class="w3-top">
    <div class="w3-bar w3-crimson w3-card">
      <span class="w3-bar-item w3-left" style="font-size: 20px">
        <i class="fa fa-life-ring"></i> Merci,<?php echo htmlspecialchars($_SESSION['nom'] . ' ' . $_SESSION['prenom']); ?>
      </span>
      <div class="w3-right w3-hide-small" style="margin-top: 5px; margin-right: 5px;">
        <a href="logout.php" class="w3-bar-item w3-button w3-hover-teal">
          <i class="fa fa-sign-out"></i> Déconnexion
        </a>
      </div>
    </div>
  </div>

  <!-- Message de confirmation -->
  <main class="bgimg-1 w3-display-container w3-grayscale-min"
        style="display: flex; justify-content: center; align-items: center; padding: 150px 0;">
    <div class="msg-container">
      <h2><i class="fa fa-check-circle"></i> Ticket créé avec succès !</h2>
      <p>Merci                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   <?php echo htmlspecialchars($_SESSION['prenom']); ?>, votre demande a bien été enregistrée.</p>
      <a href="formulaire_ticket.php" class="btn"><i class="fa fa-plus"></i> Créer un autre ticket</a>
      <a href="liste_tickets.php" class="btn"><i class="fa fa-list"></i> Voir mes tickets</a>
    </div>
  </main>

</body>
</html>

