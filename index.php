<?php require 'config.php'; ?>
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
                $tasksClass = $row['is_complited'] ? 'complited' : '';
                ?>

                <div class="task <?= $taskClass ?>">
                    <h3><?= htmlspecialchars($row['title']) ?></h3>
                    <p><?= htmlspecialchars($row['description']) ?></p>
                    <small>
                        Дата создания: <?= $row['created_at'] ?> | 
                        Статус: <?= $row['is_complited'] ? 'Выполнено' : 'В процессе' ?>
                    </small>
                </div>

                <?php endwhile; ?>
        <?php } else {
            echo '<p>Пока нет задач</p>';
        }
        ?>

        <a href="create.php">Добавить задачу</a>
    </body>
</html>