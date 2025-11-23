<?php
function ErrorMsg($msg)
{
    header("Location: index.php?error=$msg");
    exit;
}

$fullDir = 'full/';
$thumbDir = 'thumbnails/';

$allowedExtensions = ['png', 'jpg', 'jpeg'];

$errorMsg = '';

// проверка выбора файла
if (!isset($_FILES['image'])) {
    ErrorMsg('Файл не выбран.');
}

$file = $_FILES['image'];
// проверка на ошибки загрузки
if ($file['error'] !== UPLOAD_ERR_OK) {
    ErrorMsg('Ошибка загрузки файла.');
}

$fileName = $file['name'];
$tmpPath = $file['tmp_name'];

$extention = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $tmpPath);
finfo_close($finfo);

// проверка допустимого типа файла
$errorFileType = false;
switch ($mimeType) {
    case 'image/png':
        if ($extention !== 'png')
            $errorFileType = true;
        break;
    case 'image/jpeg':
        if (!($extention === 'jpg' || $extention === 'jpeg'))
            $errorFileType = true;
        break;
    default:
        $errorFileType = true;
}
if ($errorFileType) {
    ErrorMsg('Не верный тип файла.');
}


$fullPath = $fullDir . $fileName;
// проверка дубликата
if (file_exists($fullPath)) {
    ErrorMsg('Файл уже существует.');
}



// вотермарка
$watermarkPath = 'img/watermark.png';
if (file_exists($watermarkPath)) {
switch ($mimeType) {
    case 'image/png':
        $imgBody = imagecreatefrompng($tmpPath);
        $watermark = imagecreatefrompng($watermarkPath);

        $imgWidth = imagesx($imgBody);
        $imgHeight = imagesy($imgBody);

        $watermarkWidth = imagesx($watermark);
        $watermarkHeight = imagesy($watermark);

        imagecopy($imgBody, $watermark, 0, 0, 0, 0, $watermarkWidth, $watermarkHeight);

        imagepng($imgBody, $fullPath);

        imagedestroy($imgBody);
        imagedestroy($watermark);
        break;

    case 'image/jpeg':
        $imgBody = imagecreatefromjpeg($tmpPath);
        $watermark = imagecreatefrompng($watermarkPath);

        $imgWidth = imagesx($imgBody);
        $imgHeight = imagesy($imgBody);

        $watermarkWidth = imagesx($watermark);
        $watermarkHeight = imagesy($watermark);

        imagecopy($imgBody, $watermark, 0, 0, 0, 0, $watermarkWidth, $watermarkHeight);

        imagejpeg($imgBody, $fullPath);

        imagedestroy($imgBody);
        imagedestroy($watermark);
        break;
}
}

// миниатюра
switch ($mimeType) {
    case 'image/png':
        $imgSrc = imagecreatefrompng($fullPath);
        break;
    case 'image/jpeg':
        $imgSrc = imagecreatefromjpeg($fullPath);
        break;
}

$srcWidth = imagesx($imgSrc);
$srcHeight = imagesy($imgSrc);

$thumbWidth = 150;
$thumbHeight = intval($srcHeight * ($thumbWidth / $srcWidth));

$thumb = imagecreatetruecolor($thumbWidth, $thumbHeight);
imagecopyresampled(
    $thumb,
    $imgSrc,
    0,
    0,
    0,
    0,
    $thumbWidth,
    $thumbHeight,
    $srcWidth,
    $srcHeight
);

$thumbPath = $thumbDir . $fileName;
switch ($mimeType) {
    case 'image/png':
        imagepng($thumb, $thumbPath);
        break;
    case 'image/jpeg':
        imagejpeg($thumb, $thumbPath);
        break;
}

imagedestroy($imgSrc);
imagedestroy($thumb);


header('Location: index.php');
exit;

?>