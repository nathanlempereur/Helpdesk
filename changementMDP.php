<!DOCTYPE html>
<html lang="fr">

<head>
  <title>Changement mot de passe</title>
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

    .Logo {
      width: 100px;
      height: 100px;
    }

    .form-container {
      background-color: white;
      padding: 40px;
      margin: 0 20px;
      width: 100%;
      max-width: 700px;
      border-radius: 15px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
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
      padding: 12px 20px;
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
        <i class="fa fa-life-ring"></i> Changement de mot de passe : Bonjour !
      </span>
      <div class="w3-right w3-hide-small" style="margin-top: 5px; margin-right: 5px;">
        <a href="../index.html" class="w3-bar-item w3-button w3-hover-teal">
          <i class="fa fa-home"></i> Accueil
        </a>
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
      <h3><i class="fa fa-lock"></i> Changement du mot de passe</h3>

      <form method="post" action="traitementMDP.php" enctype="multipart/form-data" class="w3-container w3-padding-16 w3-white w3-round-large">

        <!-- Informations -->
        <label for="nom"><strong>Votre nom :</strong></label>
        <input type="text" id="nom" name="nom" class="w3-input w3-border" maxlength="255" required>
        <br><br>

        <label for="prenom"><strong>Votre prénom :</strong></label>
        <input type="text" id="prenom" name="prenom" class="w3-input w3-border" maxlength="255" required>
        <br><br>

        <label for="user"><strong>Votre identifiant :</strong></label>
        <input type="text" id="user" name="user" class="w3-input w3-border" maxlength="255" required>
        <br><br>

        <label for="Nmdp"><strong>Votre nouveau mot de passe :</strong></label>
        <input type="password" id="Nmdp" name="Nmpd" class="w3-input w3-border" maxlength="255" required>
        <br><br>

        <label for="Nmdp2"><strong>Confirmer votre nouveau mot de passe :</strong></label>
        <input type="password" id="Nmdp2" name="Nmpd2" class="w3-input w3-border" maxlength="255" required>
        <br><br>


        <!-- Soumission -->
        <button type="submit" class="w3-button w3-crimson w3-hover-teal w3-round-large">
        <i class="fa fa-paper-plane" aria-hidden="true"></i> Envoyer le mot de passe
        </button>
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
