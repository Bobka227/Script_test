<?php
include '../testRecepies.php';
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Рецепты</title>
    <style>
        /* Ваши CSS стили */
    </style>
</head>
<body>
<ul>
    <?php
    // Делаем переменную $recipes доступной здесь
    global $recipes;
    if (!empty($recipes)) {
        foreach ($recipes as $recipe) {
            echo "<li>";
            echo "<h2>" . htmlspecialchars($recipe['name']) . "</h2>";
            echo "<img src='qr_codes/qr_code_" . $recipe['id'] . ".png' alt='QR-код для " . htmlspecialchars($recipe['name']) . "'>";
            echo "</li>";
        }
    } else {
        echo "Нет рецептов для отображения.";
    }
    ?>
</ul>

</body>
</html>