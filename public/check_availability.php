<?php
$host = 's554ongw9quh1xjs.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
$dbname = 'hoc3ablulex394pb';
$username = 'emk2ggh76qbpq4ml';
$password = 'lf9c0g2qky76la6x';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['field']) && isset($_GET['value'])) {
        $field = $_GET['field'];
        $value = $_GET['value'];

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE $field = :value");
        $stmt->execute(['value' => $value]);
        $count = $stmt->fetchColumn();

        echo json_encode(['available' => $count == 0]);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
