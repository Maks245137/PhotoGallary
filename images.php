<?php
$thumbDir = "thumbnails/";
$fullDir = "full/";
$files = scandir($thumbDir);

foreach ($files as $file) {
    if ($file === "." || $file === "..") continue;

    $thumbPath = $thumbDir . $file;
    $fullPath = $fullDir . $file;

    if (file_exists($fullPath)) {
        echo '<a href="' . htmlspecialchars($fullPath) . '" target="_blank" rel="noopener noreferrer">';
        echo '<img src="' . htmlspecialchars($thumbPath) . '" alt="Миниатюра">';
        echo '</a>';
    }
}
