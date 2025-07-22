<?php
session_start();
require '../config/database.php';

if (isset($_GET['id'])) {
    try {
        $check = $pdo->prepare("SELECT id FROM tasks WHERE id = ?");
        $check->execute([$_GET['id']]);

        if ($check->fetch()) {
            $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            $_SESSION['succses'] = "Задача удалена";
        }
        else {
            $_SESSION['error'] = "Задача не найдена";
        }
    }
    catch (PDOException $e) {
        $_SESSION['error'] = "Ошибка удаления" . $e->getMessage();
    }
}
header('Location: index.php');
exit;
?>