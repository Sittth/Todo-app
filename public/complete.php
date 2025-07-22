<?php
require '../config/database.php';

if (isset($_POST['id'])) {
    $completed = isset($_POST['completed']) ? 1 : 0;
    
    try {
        $stmt = $pdo->prepare("UPDATE tasks SET is_completed = ? WHERE id = ?");
        $stmt->execute([$completed, $_POST['id']]);

        if ($stmt->rowCount() === 0) {
            $_SESSION['error'] = 'Задача не найдена';
        }
    }
    catch (PDOException $e) {
        $_SESSION['error'] = "Ошибка обновления: " . $e->getMessage();
    }
}
header('Location: index.php');
?>