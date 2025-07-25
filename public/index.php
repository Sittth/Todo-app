<?php
session_start();
require '../config/database.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>To-Do List</title>
    </head>
    <body>
        <h1>Список задач</h1>

        <?php 
        if(isset($_SESSION['error'])): ?>
            <div class="error"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php 
        if(isset($_SESSION['success'])): ?>
            <div class="success"><?= $_SESSION['success'] ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php
        $stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$_SESSION['user_id']]);
        if ($stmt->rowCount() > 0) {      
            while ($row = $stmt -> fetch()):
                $taskClass = $row['is_completed'] ? 'completed' : '';
                ?>

                <div class="task <?= $taskClass ?>">
                    <h3><?= htmlspecialchars($row['title']) ?></h3>
                    <p><?= htmlspecialchars($row['description']) ?></p>
                    <small>
                        Дата создания: <?= htmlspecialchars($row['created_at']) ?> | 
                        Статус: <?= $row['is_completed'] ? 'Выполнено' : 'В процессе' ?>
                    </small>
                    <a href="edit.php?id=<?= $row['id'] ?>">Обновить задачу</a>
                    <form action="delete.php" method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES) ?>">
                        <button type="submit">Удалить задачу</button>
                    </form>
                    <form action="complete.php" method="POST" style="display:inline;">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES) ?>">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <input type="checkbox" name="completed" onchange="this.form.submit()" <?= $row['is_completed'] ? 'checked' : '' ?>>
                    </form>
                </div>

                <?php endwhile; ?>
        <?php } else {
            echo '<p>Пока нет задач</p>';
        }
        ?>
        <a href="create.php">Добавить задачу</a>
        <a href="logout.php"><br>Выйти из профиля</a>
    </body>
</html>