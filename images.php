<?php
$thumbDir = "thumbnails/";
$fullDir = "full/";
$dataFile = "data.json";

$imagesPerPage = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;



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