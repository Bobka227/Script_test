<?php
session_start();

$inactive = 1200; 

if (isset($_SESSION['last_activity'])) {
    $session_lifetime = time() - $_SESSION['last_activity'];

    if ($session_lifetime > $inactive) {
        session_unset(); 
        session_destroy(); 
        header("Location: register.php"); 
        exit(); 
    }
}

// Обновляем время последней активности
$_SESSION['last_activity'] = time();
?>
