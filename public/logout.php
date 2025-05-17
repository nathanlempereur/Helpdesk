<?php
session_start();
require_once __DIR__ . '/../includes/logger.php';
writeLog('Déconnexion', "Utilisateur {$_SESSION['prenom']} {$_SESSION['nom']} déconnecté");
session_unset();
session_destroy();
header("Location: index.php");
