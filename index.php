<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>Галерея</title>
</head>

<body>
    <h1>Галерея изображений</h1>

    <!-- Форма для загрузки картинки -->
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <input type="file" name="image" accept=".png,.jpg,.jpeg" required><br>
        <textarea name="description" placeholder="Введите описание"></textarea><br>
        <input type="submit" value="Отправить"><br>
    </form>

    <!-- Вывод ошибок -->
    <div class="error">
        <?php
        if (isset($_GET['error'])) {
            echo htmlspecialchars($_GET['error']);
        }
        ?>
    </div>

    <!-- Вывод миниатюр -->
    <div class="image-grid">
        <?php require 'images.php' ?>
    </div>

    <!-- Пагинация -->
    <div class="pagination">
  <?php if ($page > 1): ?>
    <a href="?page=<?php echo $page - 1; ?>">Предыдущая</a>
  <?php endif; ?>

  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
    <?php if ($i == $page): ?>
      <strong><?php echo $i; ?></strong>
    <?php else: ?>
      <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
    <?php endif; ?>
  <?php endfor; ?>

  <?php if ($page < $totalPages): ?>
    <a href="?page=<?php echo $page + 1; ?>">Следующая</a>
  <?php endif; ?>
</div>


</body>

</html>