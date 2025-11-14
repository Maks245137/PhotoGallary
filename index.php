<!--
    перед выполнением прописать в php.ini
    extension=gd
    extension=fileinfo
-->

<?php
// Настраиваем параметры пагинации
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$perPage = 3;
$thumbnailDir = 'thumbnails';

// Получаем список всех миниатюр
$images = array_diff(scandir($thumbnailDir), array('.', '..'));
sort($images);

// Вычисляем смещение для пагинации
$totalImages = count($images);
$totalPages = ceil($totalImages / $perPage);

// Обеспечиваем, чтобы текущая страница была в диапазоне
if ($page < 1)
  $page = 1;
if ($page > $totalPages)
  $page = $totalPages;

// Выбираем изображения для текущей страницы
$pagedImages = array_slice($images, ($page - 1) * $perPage, $perPage);

// Параметры для отображения диапазона страниц
$pagesToShow = 2; // сколько страниц показывать слева и справа
$startPage = max(1, $page - $pagesToShow);
$endPage = min($totalPages, $page + $pagesToShow);
?>

<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8" />
  <title>Галерея изображений</title>
  <style>
    .gallery {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
    }

    .thumb {
      width: 150px;
      height: 150px;
      overflow: hidden;
    }

    .thumb img {
      width: 100%;
      height: auto;
      cursor: pointer;
    }

    .pagination {
      margin-top: 20px;
    }

    .error {
      color: red;
    }
  </style>
  <script>
    function openImage(imageName) {
      window.open('full/' + imageName, '_blank');
    }
  </script>
</head>

<body>

  <h1>Галерея изображений</h1>

  <div class="gallery">
    <?php foreach ($pagedImages as $img): ?>
      <div class="thumb">
        <img src="<?= htmlspecialchars($thumbnailDir . '/' . $img) ?>" alt="Image"
          onclick="openImage('<?= htmlspecialchars($img) ?>')" />
      </div>
    <?php endforeach; ?>
  </div>

  <div class="pagination">
    <?php if ($page > 1): ?>
      <a href="?page=<?= $page - 1 ?>">
        << /a>
        <?php endif; ?>

        <?php
        // Показываем номера страниц в диапазоне
        for ($i = $startPage; $i <= $endPage; $i++):
          if ($i == $page):
            echo "<strong>$i</strong>"; // текущая страница выделена
          else:
            echo "<a href=\"?page=$i\">$i</a>";
          endif;
        endfor;
        ?>

        <?php if ($page < $totalPages): ?>
          <a href="?page=<?= $page + 1 ?>">></a>
        <?php endif; ?>
  </div>

  <h2>Загрузить изображение</h2>

  <form action="upload.php" method="post" enctype="multipart/form-data">
    <input type="file" name="image" accept=".png,.jpg,.jpeg" required />
    <button type="submit">Загрузить</button>
  </form>

  <div class="error">
    <?php
    if (isset($_GET['error'])) {
      echo htmlspecialchars($_GET['error']);
    }
    ?>
  </div>

</body>

</html>