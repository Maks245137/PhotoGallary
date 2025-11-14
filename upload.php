<?php
// Папки назначения
$fullDir = 'full';
$thumbDir = 'thumbnails';

$allowedExtensions = ['png', 'jpg', 'jpeg'];
$errorMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['image'])) {
        header('Location: index.php?error=Файл не выбран');
        exit;
    }

    $file = $_FILES['image'];

    // Проверка на ошибки загрузки
    if ($file['error'] !== UPLOAD_ERR_OK) {
        header('Location: index.php?error=Ошибка загрузки файла');
        exit;
    }

    $filename = $file['name'];
    $tmpPath = $file['tmp_name'];

    //Проверка, что изображение - это изображение
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $tmpPath);
    finfo_close($finfo);

    $allowedMimes = ['image/png', 'image/jpeg'];

    if (!in_array($mime, $allowedMimes)) {
        header('Location: index.php?error=Файл не является изображением');
        exit;
    }


    // Определение расширения
    $imageInfo = getimagesize($tmpPath);
    $ext = $imageInfo[2];
    // $ext = strtolower($type);

    // // Проверка расширения
    // if (!in_array($ext, $allowedExtensions)) {
    //     header('Location: index.php?error=Неподдерживаемый формат файла');
    //     exit;
    // }



    // Проверка, является ли файл изображением
    // $imageInfo = getimagesize($tmpPath);
    // if ($imageInfo === false) {
    //     header('Location: index.php?error=Файл не является изображением');
    //     exit;
    // }



    // Проверка существования файла
    $destFull = $fullDir . '/' . $filename;
    if (file_exists($destFull)) {
        header('Location: index.php?error=Файл уже существует');
        exit;
    }

    // Перемещаем оригинал
    if (!move_uploaded_file($tmpPath, $destFull)) {
        header('Location: index.php?error=Не удалось сохранить файл');
        exit;
    }

    // Создаем миниатюру
    $imgResource = null;

    switch ($ext) {
        case IMAGETYPE_PNG:
            $imgResource = imagecreatefrompng($destFull);
            break;
        case IMAGETYPE_JPEG:
            $imgResource = imagecreatefromjpeg($destFull);
            break;
    }


    if ($imgResource === null) {
        // Удаляем загруженный файл если не удалось создать изображение
        unlink($destFull);
        header('Location: index.php?error=Ошибка обработки изображения');
        exit;
    }

    // Размеры
    $width = imagesx($imgResource);
    $height = imagesy($imgResource);
    $thumbWidth = 150;
    $thumbHeight = intval($height * ($thumbWidth / $width));

    $thumb = imagecreatetruecolor($thumbWidth, $thumbHeight);
    imagecopyresampled(
        $thumb,
        $imgResource,
        0,
        0,
        0,
        0,
        $thumbWidth,
        $thumbHeight,
        $width,
        $height
    );

    // Сохраняем миниатюру
    $thumbPath = $thumbDir . '/' . $filename;
    switch ($ext) {
        case IMAGETYPE_PNG:
            imagepng($thumb, $thumbPath);
            break;
        case IMAGETYPE_JPEG:
            imagejpeg($thumb, $thumbPath);
            break;
    }


    imagedestroy($imgResource);
    imagedestroy($thumb);

    header('Location: index.php');
    exit;
} else {
    header('Location: index.php?error=Некорректный запрос');
    exit;
}
?>