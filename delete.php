<?php
require 'config.php';

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->execute([$_GET['id']]);
}
header('Location: index.php');
?>