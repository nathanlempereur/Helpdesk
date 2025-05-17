<?php
// Connexion à la base de données
$host     = 'localhost';
$dbname   = 'SupportTickets';
$username = 'ticket';
$password = 'btsinfo'; // Remplacez par votre mot de passe si nécessaire

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Vérifier si tous les champs sont présents
if (isset($_POST['nom'], $_POST['prenom'], $_POST['user'], $_POST['Nmpd'], $_POST['Nmpd2'])) {

    $nom          = trim($_POST['nom']);
    $prenom       = trim($_POST['prenom']);
    $usernameForm = trim($_POST['user']);
    $nouveau_mdp  = trim($_POST['Nmpd']);
    $nouveau_mdp2 = trim($_POST['Nmpd2']);

    // (Optionnel) Hachage du mot de passe
    // $mot_de_passe_hash = password_hash($nouveau_mdp, PASSWORD_DEFAULT);
    $mot_de_passe_hash = $nouveau_mdp;

    // Vérifier si l'utilisateur existe
    $checkStmt = $pdo->prepare("SELECT id FROM users WHERE nom = :nom AND prenom = :prenom AND username = :username");
    $checkStmt->execute([
        ':nom'      => $nom,
        ':prenom'   => $prenom,
        ':username' => $usernameForm,
    ]);

    if ($checkStmt->rowCount() > 0) {
        // Vérifier si les deux mots de passe correspondent
        if ($nouveau_mdp !== $nouveau_mdp2) {
            echo "<script>alert('Les mots de passe ne correspondent pas.'); window.history.back();</script>";
            require_once __DIR__ . '/../includes/logger.php';
            writeLog('Changement MDP', "Une tentative de changement à été faite pour {$usernameForm} avec une erreur de mot de passe.");
            exit;
        } else {
            // Utilisateur trouvé et mdp OK, mise à jour du mot de passe
            $updateStmt = $pdo->prepare("UPDATE users SET pass = :pass WHERE nom = :nom AND prenom = :prenom AND username = :username");
            $updateStmt->execute([
                ':pass'     => $mot_de_passe_hash,
                ':nom'      => $nom,
                ':prenom'   => $prenom,
                ':username' => $usernameForm,
            ]);
            echo "<script>alert('Mot de passe changé avec succès.'); window.location.href='index.php';</script>";
            require_once __DIR__ . '/../includes/logger.php';
            writeLog('Changement MDP', "Le mot de passe de {$nom} {$prenom} à été changer.");
        }
    } else {
        echo "<script>alert('Utilisateur non trouvé ou informations incorrectes.'); window.history.back();</script>";
        require_once __DIR__ . '/../includes/logger.php';
        writeLog('Changement MDP', 'Une tentative de changement à été faite avec des informations éronnées.');
    }

} else {
    header("Location: changementMDP.php");
    exit;
}
