<?php require '../config/database.php'; ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>To-Do List</title>
    </head>
    <body>
        <h1>Список задач</h1>

        <?php
        $stmt = $pdo -> query("SELECT * FROM tasks ORDER BY created_at DESC");
        if ($stmt->rowCount() > 0) {      
            while ($row = $stmt -> fetch()):
                $tasksClass = $row['is_completed'] ? 'completed' : '';
                ?>

                <div class="task <?= $taskClass ?>">
                    <h3><?= htmlspecialchars($row['title']) ?></h3>
                    <p><?= htmlspecialchars($row['description']) ?></p>
                    <small>
                        Дата создания: <?= $row['created_at'] ?> | 
                        Статус: <?= $row['is_completed'] ? 'Выполнено' : 'В процессе' ?>
                    </small>
                    <a href="edit.php?id=<?= $row['id'] ?>">Обновить задачу</a>
                    <a href="delete.php?id=<?= $row['id'] ?>">Удалить задачу</a>
                    <form action="complete.php" method="POST" style="display:inline;">
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
    </body>
</html>