<?php
session_start();
require_once __DIR__ . '/../includes/logger.php';
writeLog('Déconnexion', "Administrateur {$_SESSION['prenoma']} {$_SESSION['noma']} déconnecté");
session_unset();
session_destroy();
header("Location: index.php");
