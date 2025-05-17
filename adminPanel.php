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

    // Récupérer le nombre de ticket max de 1 utilisateur
    $requete = $bdd->query("
    SELECT nom, prenom, COUNT(*) AS total
    FROM tickets
    GROUP BY nom, prenom
    ORDER BY total DESC
    LIMIT 1;
");
    $TicketsMaxData = $requete->fetch(PDO::FETCH_ASSOC);

    if ($TicketsMaxData) {
        $TicketsMax     = $TicketsMaxData['total'];
        $TicketsMaxUser = $TicketsMaxData['nom'] . ' ' . $TicketsMaxData['prenom'];
    } else {
        $TicketsMax     = "Aucun ticket";
        $TicketsMaxUser = "";
    }

    // Récupérer le nombre de tickets en cours
    $TicketsEnCours = $bdd->query("SELECT COUNT(*) FROM tickets WHERE id_statut = 2")->fetchColumn();

    // Récupérer le nombre de tickets ouvert
    $TicketsFermer = $bdd->query("SELECT COUNT(*) FROM tickets WHERE id_statut = 3")->fetchColumn();

    // Récupérer le nombre de tickets ouvert
    $TicketsOuvert = $bdd->query("SELECT COUNT(*) FROM tickets WHERE id_statut = 1")->fetchColumn();

    // Récupérer le nombre total de tickets
    $totalTickets = $bdd->query("SELECT COUNT(*) FROM tickets")->fetchColumn();

    // Récupérer le nombre de tickets à priorité basse (id_priorite = 1)
    $basseTickets = $bdd->query("SELECT COUNT(*) FROM tickets WHERE id_priorite = 1")->fetchColumn();

    // Éviter la division par 0
    $pourcentageBasse = ($totalTickets > 0) ? round(($basseTickets / $totalTickets) * 100) : 0;

    // Récupérer le nombre de tickets à priorité moyenne (id_priorite = 2)
    $moyenTickets = $bdd->query("SELECT COUNT(*) FROM tickets WHERE id_priorite = 2")->fetchColumn();

    // Éviter la division par 0
    $pourcentageMoyen = ($totalTickets > 0) ? round(($moyenTickets / $totalTickets) * 100) : 0;

    // Récupérer le nombre de tickets à priorité moyenne (id_priorite = 3)
    $élevéeTickets = $bdd->query("SELECT COUNT(*) FROM tickets WHERE id_priorite = 3")->fetchColumn();

    // Éviter la division par 0
    $pourcentageElevée = ($totalTickets > 0) ? round(($élevéeTickets / $totalTickets) * 100) : 0;

    // Récupérer le nombre de tickets à priorité moyenne (id_priorite = 4)
    $critiqueTickets = $bdd->query("SELECT COUNT(*) FROM tickets WHERE id_priorite = 4")->fetchColumn();

    // Éviter la division par 0
    $pourcentageCritique = ($totalTickets > 0) ? round(($critiqueTickets / $totalTickets) * 100) : 0;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ticket_id'], $_POST['nouveau_statut'])) {
        $update = $bdd->prepare("UPDATE tickets SET id_statut = :statut WHERE id = :id");
        $update->execute([
            ':statut' => intval($_POST['nouveau_statut']),
            ':id'     => intval($_POST['ticket_id']),
        ]);
    }

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
html,body,h1,h2,h3,h4,h5 {font-family: "Raleway", sans-serif}
a {
  text-decoration: none;
}


.dark-mode {
  background-color: #121212 !important;
  color: #f1f1f1 !important;
}

.dark-mode body {
  background-color: #1e1e1e !important;
  color: #f1f1f1 !important;
}

.dark-mode .w3-panel {
  background-color: #1e1e1e !important;
  color: #f1f1f1 !important;
}

.dark-mode .w3-sidebar {
  background-color:rgb(66, 65, 65) !important;
  color: #f1f1f1 !important;
}
</style>
</head>
<body class="w3-light-grey">

<!-- Sidebar/menu -->
<nav class="w3-sidebar w3-collapse w3-white w3-animate-left" style="z-index:3;width:300px;" id="mySidebar"><br>
  <div class="w3-container w3-row">
    <div class="w3-col s4">
    </div>
    <div class="w3-col s8 w3-bar">
      <span style="font-size: 20px">Bonjour, <strong><?php echo $_SESSION['prenoma'] ?> !</strong></span><br><br>
      <a href="https: //workspace.google.com/intl/fr/gmail/
" class="w3-bar-item w3-button"><i class="fa fa-envelope"></i></a>
      <a href="logout.php" class="w3-bar-item w3-button"><i class="fa fa-sign-out"></i></a>
      <a class="w3-bar-item w3-button" onclick="toggleDarkMode()" title="Changer le mode"><i class="fa fa-moon-o"></i></a>
    </div>
  </div>
  <hr>
  <div class="w3-container">
    <h5>Mon menu</h5>
  </div>
  <div class="w3-bar-block">
    <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="w3_close()" title="close menu"><i class="fa fa-remove fa-fw"></i>  Close Menu</a>
    <a href="tableLogs.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-users fa-fw"></i>  Traffic</a>
    <a href="tableServices.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-bank fa-fw"></i>  Services</a>
    <a href="#" class="w3-bar-item w3-button w3-padding"><i class="fa fa-diamond fa-fw"></i>  #</a>
    <a href="#" class="w3-bar-item w3-button w3-padding"><i class="fa fa-bell fa-fw"></i>  #</a>
    <a href="#" class="w3-bar-item w3-button w3-padding"><i class="fa fa-history fa-fw"></i>  #</a>
    <a href="#" class="w3-bar-item w3-button w3-padding"><i class="fa fa-cog fa-fw"></i>  #</a><br><br>
  </div>
</nav>


<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">

  <!-- Header -->
  <header class="w3-container" style="padding-top:22px">
    <h5><b><i class="fa fa-dashboard"></i> Mon tableau de bord</b></h5>
  </header>

  <div class="w3-row-padding w3-margin-bottom">
    <div class="w3-quarter w3-btn w3-red"><a href="tableTickets.php">
      <div class="w3-container w3-padding-16">
        <div class="w3-right">
          <h3></h3>
        </div>
        <div class="w3-clear"></div>
        <h4><p class="w3-xlarge">Liste des Tickets<i class="w3-margin-left fa fa-comment"></i></p></h4>
      </div>
    </div></a>
    <div class="w3-quarter w3-btn w3-blue"><a href="tableUtilisateurs.php">
      <div class="w3-container w3-padding-16">
        <div class="w3-right">
          <h3></h3>
        </div>
        <div class="w3-clear"></div>
        <h4> <p class="w3-xlarge">Liste des Utilisateurs<i class="w3-margin-left fa fa-search"></i></p></h4>
      </div>
    </div></a>
    <div class="w3-quarter w3-btn w3-teal "><a href="tableAdministrateurs.php">
      <div class="w3-container w3-padding-16">
        <div class="w3-right">
          <h3></h3>
        </div>
        <div class="w3-clear"></div>
        <h4> <p class="w3-xlarge">Liste des Administrateurs<i class="w3-margin-left fa fa-user"></i></p></h4>
      </div>
    </div></a>
    <div class="w3-quarter w3-btn w3-black w3-text-white"><a href="logout.php">
      <div class="w3-container w3-text-white w3-padding-16">
        <div class="w3-right">
          <h3></h3>
        </div>
        <div class="w3-clear"></div>
        <h4> <p class="w3-xlarge">Déconnexion<i class="w3-margin-left fa fa-sign-out"></i></button></a></p></h4>
        </div>
    </div>
  </div>

  <div class="w3-panel" style="background-color:rgb(206, 206, 206); padding-bottom: 40px; margin-left:15px; margin-right: 15px;">
    <div class="w3-row-padding" style="margin:0 -16px">
      <div class="w3-third">

     </div>
      <div class="w3-twothird">
  <h5>Tickets en cours (<?php echo $TicketsEnCours ?>) :</h5>
<table class="w3-table w3-striped w3-white">
  <tr>
    <th>ID</th>
    <th>Nom</th>
    <th>Prénom</th>
    <th>Titre</th>
    <th>Type de problème</th>
    <th>Description</th>
    <th>Priorité</th>
    <th>Date création</th>
    <th>Statut</th>
  </tr>

<?php
    $stmt = $bdd->prepare("
  SELECT
    t.id,
    t.nom,
    t.prenom,
    td.typed AS type_demande,
    t.titre,
    t.description,
    p.priorited AS priorite,
    p.id_priorite,
    s.systemd AS systeme,
    sv.serviced AS service,
    st.statut,
    st.id_statut,
    t.date_creation
  FROM tickets t
  JOIN type_demande td ON t.id_demande = td.id_demande
  JOIN priorite p ON t.id_priorite = p.id_priorite
  JOIN systeme s ON t.id_systeme = s.id_systeme
  JOIN services sv ON t.id_service = sv.id_service
  JOIN statuts st ON t.id_statut = st.id_statut
  WHERE t.id_statut = 2
  ORDER BY t.date_creation DESC
  LIMIT 10
");
    $stmt->execute();

    while ($ticket = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr style='background-color: #FFC107;'>";
        echo '<td>' . htmlspecialchars($ticket['id']) . '</td>';
        echo '<td>' . htmlspecialchars($ticket['nom']) . '</td>';
        echo '<td>' . htmlspecialchars($ticket['prenom']) . '</td>';
        echo '<td>' . htmlspecialchars($ticket['titre']) . '</td>';
        echo '<td>' . htmlspecialchars($ticket['type_demande']) . '</td>';
        echo '<td>' . htmlspecialchars($ticket['description']) . '</td>';
        echo '<td>' . htmlspecialchars($ticket['priorite']) . '</td>';
        echo '<td>' . htmlspecialchars($ticket['date_creation']) . '</td>';

        // Formulaire de changement de statut
        echo '<td>
  <form method="POST" class="inline-form">
    <input type="hidden" name="ticket_id" value="' . $ticket['id'] . '">
    <select name="nouveau_statut" onchange="this.form.submit()">
      <option value="1" ' . ($ticket['id_statut'] == 1 ? 'selected' : '') . '>Ouvert</option>
      <option value="2" ' . ($ticket['id_statut'] == 2 ? 'selected' : '') . '>En cours</option>
      <option value="3" ' . ($ticket['id_statut'] == 3 ? 'selected' : '') . '>Fermé</option>
    </select>
  </form>
</td>';

        echo "</tr>";
    }
?>
</table>

  <br><br>

  <h5>Tickets ouvert en attente (<?php echo $TicketsOuvert ?>) :</h5>
  <table class="w3-table w3-striped w3-white">
  <tr>
    <th>ID</th>
    <th>Nom</th>
    <th>Prénom</th>
    <th>Titre</th>
    <th>Type de problème</th>
    <th>Description</th>
    <th>Priorité</th>
    <th>Date création</th>
    <th>Statut</th>
  </tr>

<?php
    $stmt = $bdd->prepare("
  SELECT
    t.id,
    t.nom,
    t.prenom,
    td.typed AS type_demande,
    t.titre,
    t.description,
    p.priorited AS priorite,
    p.id_priorite,
    s.systemd AS systeme,
    sv.serviced AS service,
    st.statut,
    st.id_statut,
    t.date_creation
  FROM tickets t
  JOIN type_demande td ON t.id_demande = td.id_demande
  JOIN priorite p ON t.id_priorite = p.id_priorite
  JOIN systeme s ON t.id_systeme = s.id_systeme
  JOIN services sv ON t.id_service = sv.id_service
  JOIN statuts st ON t.id_statut = st.id_statut
  WHERE t.id_statut = 1
  ORDER BY t.date_creation DESC
  LIMIT 10
");
    $stmt->execute();

    while ($ticket = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr style='background-color:rgb(255, 29, 29);'>";
        echo '<td>' . htmlspecialchars($ticket['id']) . '</td>';
        echo '<td>' . htmlspecialchars($ticket['nom']) . '</td>';
        echo '<td>' . htmlspecialchars($ticket['prenom']) . '</td>';
        echo '<td>' . htmlspecialchars($ticket['titre']) . '</td>';
        echo '<td>' . htmlspecialchars($ticket['type_demande']) . '</td>';
        echo '<td>' . htmlspecialchars($ticket['description']) . '</td>';
        echo '<td>' . htmlspecialchars($ticket['priorite']) . '</td>';
        echo '<td>' . htmlspecialchars($ticket['date_creation']) . '</td>';

        echo '<td>
  <form method="POST" class="inline-form">
    <input type="hidden" name="ticket_id" value="' . $ticket['id'] . '">
    <select name="nouveau_statut" onchange="this.form.submit()">
      <option value="1" ' . ($ticket['id_statut'] == 1 ? 'selected' : '') . '>Ouvert</option>
      <option value="2" ' . ($ticket['id_statut'] == 2 ? 'selected' : '') . '>En cours</option>
      <option value="3" ' . ($ticket['id_statut'] == 3 ? 'selected' : '') . '>Fermé</option>
    </select>
  </form>
</td>';

        echo "</tr>";
    }
?>
  </table>
  </div>
    </div>
  </div>


  <hr>
  <div class="w3-container">
    <h5>Statistique général :</h5>
    <p>Taux de tickets à gravité basse :</p>
    <div class="w3-grey" style="border-radius: 8px; overflow: hidden;">
      <div class="w3-container w3-center w3-padding w3-green" style="width:<?php echo $pourcentageBasse ?>%"><?php echo $pourcentageBasse . '%' ?></div>
    </div>

    <p>Taux de tickets à gravité moyenne :</p>
    <div class="w3-grey" style="border-radius: 8px; overflow: hidden;">
      <div class="w3-container w3-center w3-padding w3-yellow" style="width:<?php echo $pourcentageMoyen ?>%"><?php echo $pourcentageMoyen . '%' ?></div>
    </div>

    <p>Taux de tickets à gravité élevée :</p>
    <div class="w3-grey" style="border-radius: 8px; overflow: hidden;">
      <div class="w3-container w3-center w3-padding w3-orange" style="width:<?php echo $pourcentageElevée ?>%"><?php echo $pourcentageElevée . '%' ?></div>
    </div>

    <p>Taux de tickets à gravité critique :</p>
    <div class="w3-grey" style="border-radius: 8px; overflow: hidden;">
      <div class="w3-container w3-center w3-padding w3-red" style="width:<?php echo $pourcentageCritique ?>%"><?php echo $pourcentageCritique . '%' ?></div>
    </div>
  </div>
  <hr>

  <div class="w3-container">


    <h5>Informations</h5>
    <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
      <tr>
        <td>Utilisateur avec le plus de tickets :                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       <?php echo $TicketsMaxUser ?></td>
        <td><?php echo $TicketsMax ?></td>
      </tr>
      <tr>
        <td>Tickets Fermer :</td>
        <td><?php echo $TicketsFermer ?></td>
      </tr>
      <tr>
        <td>Tickets En cours :</td>
        <td><?php echo $TicketsEnCours ?></td>
      </tr>
      <tr>
        <td>Tickets Ouvert :</td>
        <td><?php echo $TicketsOuvert ?></td>
      </tr>
    </table><br>
  </div>
  <hr>
  <div class="w3-container">
    <h5>Admin</h5>
    <ul class="w3-ul w3-card-4 w3-white">
    <?php
        $stmt = $bdd->prepare("SELECT * FROM admins");
        $stmt->execute();

        while ($users = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<li class='w3-padding-16'>
                  <span class='w3-xlarge'>" . htmlspecialchars($users['prenoma']) . "</span><br>
                  </li>";
        }
    ?>
    </ul>
  </div>
  <hr>


  <div class="w3-container w3-dark-grey w3-padding-32">
    <div class="w3-row">
      <div class="w3-container w3-third">
        <h5 class="w3-bottombar w3-border-green">Demographic</h5>
        <p>Language</p>
        <p>Country</p>
        <p>City</p>
      </div>
      <div class="w3-container w3-third">
        <h5 class="w3-bottombar w3-border-red">System</h5>
        <p>Browser</p>
        <p>OS</p>
        <p>More</p>
      </div>
      <div class="w3-container w3-third">
        <h5 class="w3-bottombar w3-border-orange">Target</h5>
        <p>Users</p>
        <p>Active</p>
        <p>Geo</p>
        <p>Interests</p>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="w3-container w3-padding-16 w3-light-grey">
    <h4>FOOTER</h4>
    <p>Powered by <a href="https://www.w3schools.com/w3css/default.asp" target="_blank">w3.css</a></p>
  </footer>

  <!-- End page content -->
</div>

<script>
// Get the Sidebar
var mySidebar = document.getElementById("mySidebar");

// Get the DIV with overlay effect
var overlayBg = document.getElementById("myOverlay");

// Toggle between showing and hiding the sidebar, and add overlay effect
function w3_open() {
  if (mySidebar.style.display === 'block') {
    mySidebar.style.display = 'none';
    overlayBg.style.display = "none";
  } else {
    mySidebar.style.display = 'block';
    overlayBg.style.display = "block";
  }
}

// Close the sidebar with the close button
function w3_close() {
  mySidebar.style.display = "none";
  overlayBg.style.display = "none";
}

function toggleDarkMode() {
  const body = document.body;
  body.classList.toggle('dark-mode');

  // Sauvegarde le mode dans localStorage
  if (body.classList.contains('dark-mode')) {
    localStorage.setItem('theme', 'dark');
  } else {
    localStorage.setItem('theme', 'light');
  }
}

// Appliquer le thème au chargement si déjà défini
window.onload = () => {
  if (localStorage.getItem('theme') === 'dark') {
    document.body.classList.add('dark-mode');
  }
};
</script>

</body>
</html>
