<?php
session_start();
$_SESSION['last_activity'] = time(); // Обновляем время активности
echo json_encode(["status" => "success"]);
?>
