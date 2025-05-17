<?php
function writeLog($action, $message, $level = 'INFO')
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $rootDir = realpath(__DIR__ . '/../');
    $logDir  = $rootDir . '/logs';

    if (! is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }

    $logFile = $logDir . '/helpdesk_' . date('Y-m-d') . '.log';

    $date = date('Y-m-d H:i:s');
    $ip   = $_SERVER['REMOTE_ADDR'] ?? 'CLI';
    $user = $_SESSION['user_email'] ?? 'guest';

    $logLine = "[$date][$ip][$user][$level][$action] $message" . PHP_EOL;
    file_put_contents($logFile, $logLine, FILE_APPEND);
}
