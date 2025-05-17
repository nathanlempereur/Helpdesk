<?php
    session_start();
    header('Content-Type: text/html; charset=utf-8');

    // Initialiser les variables
    $message = '';

    // Vérifier si le formulaire a été soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Connexion à la base de données
        $servername  = "localhost";
        $username_db = "ticket";
        $password_db = "btsinfo";
        $dbname      = "SupportTickets";

        $conn = new mysqli($servername, $username_db, $password_db, $dbname);

        // Vérifier la connexion
        if ($conn->connect_error) {
            die("Échec de la connexion : " . $conn->connect_error);
        }

        // Récupérer et sécuriser les données du formulaire
        $username = trim($_POST['username']);
        $mdp      = md5($_POST['mdp']);

        // Préparer et exécuter la requête
        $stmt = $conn->prepare("SELECT Nom, Prenom, pass FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        // Vérifier si l'utilisateur existe
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($nom, $prenom, $password_bdd);
            $stmt->fetch();

            // Vérifier le mot de passe
            if ($mdp === $password_bdd) {
                $_SESSION['nom']      = $nom;
                $_SESSION['prenom']   = $prenom;
                $_SESSION['username'] = $username;
                header("Location: formulaire_ticket.php");
                require_once __DIR__ . '/../includes/logger.php';
                writeLog('Connexion', "Connexion de {$nom} {$prenom}.");
                exit;
            } else {
                echo "<script>alert('Mot de passe incorrecte'); window.location.href='index.php';</script>";
                require_once __DIR__ . '/../includes/logger.php';
                writeLog('Connexion', "Erreur de connexion pour {$nom} {$prenom} avec un mot de passe incorrecte.");

            }
        } else {
            echo "<script>alert('Utilisateur introuvable'); window.location.href='index.php';</script>";

        }

        $stmt->close();
        $conn->close();
    }
?>
<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
            /* Remove default margin */
        }

        .bgimg-1 {
            background-position: center;
            background-size: cover;
            background-image: url("../img/fond-index.png");
            min-height: 100%;
            display: flex;
            /* Use flexbox for centering */
            justify-content: center;
            /* Center horizontally */
            align-items: center;
            /* Center vertically */
        }

        #login {
            display: flex;
            border: 3px solid black;
            padding: 20px;
            width: 600px;
            /* Fixed width for consistency */
            flex-direction: column;
            border-radius: 30px;
            background-color: rgb(187, 187, 187);
            /* Slightly more opaque */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* Add shadow for depth */
        }

        #cadrelogin {
            border-bottom: 2px solid black;
            margin-bottom: 20px;
            text-align: center;
            /* Center the header text */
        }

        #login input[type="text"],
        #login input[type="password"] {
            width: 100%;
            /* Full width */
            margin: 10px 0;
            /* Vertical margin */
            padding: 10px;
            /* Increased padding */
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        #login input[type="submit"] {
            width: 100%;
            /* Full width */
            margin: 10px 0;
            /* Vertical margin */
            padding: 10px;
            /* Increased padding */
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #login input[type="submit"]:hover {
            background-color: #45a049;
        }

        .w3-bar .w3-button {
            padding: 16px;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <div class="w3-top">
        <div class="w3-bar w3-crimson w3-card" id="myNavbar">
            <h2 style="margin-left: 10px;" class="w3-left">Connexion à votre compte :</h2>
            <div class="w3-right w3-hide-small" style="margin-top: 5px; margin-right: 5px;">
                <a href="../index.html" class="w3-bar-item w3-button w3-hover-teal"><i class="fa fa-home"></i> Accueil</a>
              </div>
        </div>
    </div>

    <!-- main -->
    <main class="bgimg-1 w3-display-container w3-grayscale-min" id="home">
        <div>
            <div id="login">
                <header id="cadrelogin">
                    <h1> Connexion à votre compte : </h1>
                </header>

                <form id="loginForm" method="post">
                    <label for="username">Identifiant :</label>
                    <input type="text" id="username" name="username" placeholder="Entrez votre identifiant" required>
                    <label for="password">Mot de passe :</label>
                    <input type="password" id="mdp" name="mdp" placeholder="Entrez votre mot de passe"
                        required>
                    <input type="submit" value="Se connecter">
                    <br>
                    <a id="liencour2" href="changementMDP.php"><span> Changement de mot de passe</span></a>
                </form>
            </div>
        </div>

        <footer>
            <div class="w3-display-bottomright w3-padding-large w3-text-white">
                Powered by <a href="https://www.w3schools.com/w3css/default.asp" target="_blank">w3.css</a>
            </div>
        </footer>
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
