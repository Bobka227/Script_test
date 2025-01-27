<?php
$host = 'enqhzd10cxh7hv2e.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
$dbname = 'nb6x6m9qlsec07j8';
$username = 'wk4kwaf4w8x12twh';
$password = 'ijw8uyd2lwkgf8on';

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
