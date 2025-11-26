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
    <?php
    $thumbDir = "thumbnails/";
    $fullDir = "full/";
    $dataFile = "data.json";

    // кол-во страниц и текущая страница
    $imagesPerPage = 6;
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

    if (file_exists($dataFile)) {
      $images = json_decode(file_get_contents($dataFile), true);

      if (json_last_error() === JSON_ERROR_NONE && is_array($images)) {

        $totalPages = ceil(count($images) / $imagesPerPage);
        $startIndex = ($page - 1) * $imagesPerPage;

        $currentImages = array_slice($images, $startIndex, $imagesPerPage);

        foreach ($currentImages as $item) {
          $fullPath = $fullDir . $item['fileName'];
          $thumbPath = $thumbDir . $item['fileName'];

          if (file_exists($fullPath) && file_exists($thumbPath)) {
            echo '<div>';
            echo '<a href="' . htmlspecialchars($fullPath) . '" target="_blank" rel="noopener noreferrer">';
            echo '<img src="' . htmlspecialchars($thumbPath) . '" alt="' . htmlspecialchars($item['fileName']) . '">';
            echo '</a>';
            echo '<p>' . htmlspecialchars($item['description']) . '</p>';
            echo '</div>';
          }
        }

      }
    }

    ?>
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


  <div>
    <a href="3d.php">3д галерея</a>
  </div>

</body>

</html>