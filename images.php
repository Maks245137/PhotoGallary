<?php
$dir = "thumbnails/";
$allowedExtensions = ["jpg", "jpeg", "png"];
$files = scandir($dir);

foreach ($files as $file) {
    if ($file === "." || $file === "..") {
        continue;
    }

    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    if (in_array($ext, $allowedExtensions)) {
        echo '<img src="' . $dir . $file . '" alt="Image" onclick="windows.open($file)">';
    }
}
?>