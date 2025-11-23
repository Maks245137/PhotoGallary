<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <div class="gallery">
        <?php require 'images.php' ?>
    </div>

</body>

</html>