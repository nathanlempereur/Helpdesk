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

    // Mise à jour priorité
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ticket_id'], $_POST['nouvelle_priorite'])) {
        $update = $bdd->prepare("UPDATE tickets SET id_priorite = :priorite WHERE id = :id");
        $update->execute([
            ':priorite' => intval($_POST['nouvelle_priorite']),
            ':id'       => intval($_POST['ticket_id']),
        ]);
    }

    // Mise à jour statut
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ticket_id'], $_POST['nouveau_statut'])) {
        $update = $bdd->prepare("UPDATE tickets SET id_statut = :statut WHERE id = :id");
        $update->execute([
            ':statut' => intval($_POST['nouveau_statut']),
            ':id'     => intval($_POST['ticket_id']),
        ]);
    }

    // Fonctions de style
    function getPrioriteClass($id_priorite)
    {
        return match ($id_priorite) {
            4 => 'priorite-critique',
            3 => 'priorite-élevée',
            2 => 'priorite-moyenne',
            1 => 'priorite-basse',
            default => '',
        };
    }

    function getStatutClass($id_statut)
    {
        return match ($id_statut) {
            1 => 'statut-ouvert',
            2 => 'statut-encours',
            3 => 'statut-ferme',
            default => '',
        };
    }

    // Récupérer les données
    $priorites = [];
    $stmt      = $bdd->query("SELECT * FROM priorite");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $priorites[$row['id_priorite']] = $row['priorited'];
    }
    $stmt->closeCursor();

    $statuts = [];
    $stmt    = $bdd->query("SELECT * FROM statuts");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $statuts[$row['id_statut']] = $row['statut'];
    }
    $stmt->closeCursor();

    $types_demande = [];
    $stmt          = $bdd->query("SELECT * FROM type_demande");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $types_demande[$row['id_demande']] = $row['typed'];
    }
    $stmt->closeCursor();

    // Préparer les filtres
    $filter = [];
    $query  = "SELECT
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
JOIN statuts st ON t.id_statut = st.id_statut";

    if (isset($_GET['priorite']) && $_GET['priorite'] !== '') {
        $filter[] = "t.id_priorite = :priorite";
    }
    if (isset($_GET['statut']) && $_GET['statut'] !== '') {
        $filter[] = "t.id_statut = :statut";
    }
    if (isset($_GET['type_demande']) && $_GET['type_demande'] !== '') {
        $filter[] = "t.id_demande = :type_demande";
    }

    if (! isset($_GET['statut']) || $_GET['statut'] === '') {
        $filter[] = "t.id_statut IN (1, 2)";
    }

    if (count($filter) > 0) {
        $query .= " WHERE " . implode(' AND ', $filter);
    }

    $query .= " ORDER BY t.id DESC";

    $tickets = $bdd->prepare($query);

    if (isset($_GET['priorite']) && $_GET['priorite'] !== '') {
        $tickets->bindParam(':priorite', $_GET['priorite'], PDO::PARAM_INT);
    }
    if (isset($_GET['statut']) && $_GET['statut'] !== '') {
        $tickets->bindParam(':statut', $_GET['statut'], PDO::PARAM_INT);
    }
    if (isset($_GET['type_demande']) && $_GET['type_demande'] !== '') {
        $tickets->bindParam(':type_demande', $_GET['type_demande'], PDO::PARAM_INT);
    }

    $tickets->execute();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Liste des tickets</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Styles -->
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
    body { font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fa; }
    h1 { text-align: center; color: #343a40; }
    form.inline-form, form[method="GET"] {
        display: flex; gap: 10px; align-items: center; flex-wrap: wrap;
        justify-content: center; margin-bottom: 20px;
    }
    table {
        width: 100%; border-collapse: separate; border-spacing: 0;
        border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        background: white; padding: 20px;
    }
    th { background: #343a40; color: white; padding: 12px; text-align: center; }
    td { padding: 10px; border-bottom: 1px solid #dee2e6; text-align: center; }
    tr:hover { background-color: #f1f1f1; }
    select, button {
        padding: 5px 8px; border: 1px solid #ced4da; border-radius: 5px;
        background: #ffffff; cursor: pointer; transition: all 0.3s ease;
    }
    button:hover { background: #007BFF; color: white; border-color: #007BFF; }

    /* Priorités */
    .priorite-critique { background-color: #dc3545; color: white; font-weight: bold; border-radius: 5px; }
    .priorite-élevée { background-color: #fd7e14; color: white; font-weight: bold; border-radius: 5px; }
    .priorite-moyenne { background-color: #ffc107; color: black; font-weight: bold; border-radius: 5px; }
    .priorite-basse { background-color: #28a745; color: white; font-weight: bold; border-radius: 5px; }

    /* Statuts */
    .statut-ouvert { background-color: #17a2b8; color: white; font-weight: bold; border-radius: 5px; }
    .statut-encours { background-color: #6f42c1; color: white; font-weight: bold; border-radius: 5px; }
    .statut-ferme { background-color: #6c757d; color: white; font-weight: bold; border-radius: 5px; }
  </style>
</head>
<body>

<div class="w3-top">
    <div class="w3-bar w3-card" style="background-color: #343a40; color:white">
      <span class="w3-bar-item w3-left" style="font-size: 20px">
        <i class="fa fa-life-ring"></i> Bonjour                                                                                                                                                                                                                                            <?php echo htmlspecialchars($_SESSION['prenoma']); ?>!
      </span>
      <div class="w3-right w3-hide-small" style="margin-top: 5px; margin-right: 5px;">
        <a href="adminPanel.php" class="w3-bar-item w3-button w3-hover-teal"><i class="fa fa-home"></i> Accueil</a>
        <a href="logout.php" class="w3-bar-item w3-button w3-hover-teal"><i class="fa fa-sign-out"></i> Déconnexion</a>
      </div>
    </div>
</div>

<br><br><br>

<h1>La liste des tickets :</h1><br>

<<form method="GET" class="filter-form" style="text-align:center; margin-bottom:20px;">
  <label for="priorite">Priorité :</label>
  <select name="priorite" id="priorite" onchange="this.form.submit()">
    <option value="">Toutes</option>
    <option value="1"                                           <?php echo(isset($_GET['priorite']) && $_GET['priorite'] == 1) ? 'selected' : '' ?>>Basse</option>
    <option value="2"                                           <?php echo(isset($_GET['priorite']) && $_GET['priorite'] == 2) ? 'selected' : '' ?>>Moyenne</option>
    <option value="3"                                           <?php echo(isset($_GET['priorite']) && $_GET['priorite'] == 3) ? 'selected' : '' ?>>Haute</option>
    <option value="4"                                           <?php echo(isset($_GET['priorite']) && $_GET['priorite'] == 4) ? 'selected' : '' ?>>Critique</option>
    <option value="5"                                           <?php echo(isset($_GET['priorite']) && $_GET['priorite'] == 5) ? 'selected' : '' ?>>Indéfini</option>
  </select>

  &nbsp;&nbsp;&nbsp;

  <label for="statut">Statut :</label>
  <select name="statut" id="statut" onchange="this.form.submit()">
    <option value="">Tous</option>
    <option value="1"                                           <?php echo(isset($_GET['statut']) && $_GET['statut'] == 1) ? 'selected' : '' ?>>Ouvert</option>
    <option value="2"                                           <?php echo(isset($_GET['statut']) && $_GET['statut'] == 2) ? 'selected' : '' ?>>En cours</option>
    <option value="3"                                           <?php echo(isset($_GET['statut']) && $_GET['statut'] == 3) ? 'selected' : '' ?>>Fermé</option>
  </select>

  &nbsp;&nbsp;&nbsp;

  <label for="type_demande">Type de problème :</label>
  <select name="type_demande" id="type_demande" onchange="this.form.submit()">
    <option value="">Tous</option>
    <?php foreach ($types_demande as $id => $type): ?>
      <option value="<?php echo $id ?>"<?php echo(isset($_GET['type_demande']) && $_GET['type_demande'] == $id) ? 'selected' : '' ?>>
        <?php echo htmlspecialchars($type) ?>
      </option>
    <?php endforeach; ?>
  </select>
</form>

<table>
    <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Titre</th>
        <th>Type de problème</th>
        <th>Description</th>
        <th>Priorité</th>
        <th>Statut</th>
        <th>Date</th>
        <th>Modifier priorité</th>
        <th>Modifier statut</th>
    </tr>

<?php
    while ($row = $tickets->fetch(PDO::FETCH_ASSOC)) {
        $priorite_class = getPrioriteClass($row['id_priorite']);
        $statut_class   = getStatutClass($row['id_statut']);

        echo '<tr>';
        echo '<td>' . $row['id'] . '</td>';
        echo '<td>' . htmlspecialchars($row['nom']) . '</td>';
        echo '<td>' . htmlspecialchars($row['prenom']) . '</td>';
        echo '<td>' . htmlspecialchars($row['titre']) . '</td>';
        echo '<td>' . htmlspecialchars($row['type_demande']) . '</td>';
        echo '<td>' . htmlspecialchars($row['description']) . '</td>';
        echo '<td class="' . $priorite_class . '">' . htmlspecialchars($row['priorite']) . '</td>';
        echo '<td class="' . $statut_class . '">' . htmlspecialchars($row['statut']) . '</td>';
        echo '<td>' . htmlspecialchars($row['date_creation']) . '</td>';

        // Modifier priorité
        echo '<td><form method="POST" class="inline-form">';
        echo '<input type="hidden" name="ticket_id" value="' . $row['id'] . '">';
        echo '<select name="nouvelle_priorite">';
        foreach ($priorites as $id => $label) {
            $selected = ($id == $row['id_priorite']) ? 'selected' : '';
            echo "<option value=\"$id\" $selected>$label</option>";
        }
        echo '</select><button type="submit">✔</button></form></td>';

        // Modifier statut
        echo '<td><form method="POST" class="inline-form">';
        echo '<input type="hidden" name="ticket_id" value="' . $row['id'] . '">';
        echo '<select name="nouveau_statut">';
        foreach ($statuts as $id => $label) {
            $selected = ($id == $row['id_statut']) ? 'selected' : '';
            echo "<option value=\"$id\" $selected>$label</option>";
        }
        echo '</select><button type="submit">✔</button></form></td>';

        echo '</tr>';
    }
    $bdd = null;
?>
</table>
</body>
</html>
