<?php
session_start();
session_unset(); 
session_destroy(); 

// Возвращаем JSON-ответ
echo json_encode(['status' => 'success']);
exit();
?>