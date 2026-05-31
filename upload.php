<?php
require_once 'functions.php';

if (!isset($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
    header('Location: index.php?error=no_file');
    exit;
}

$file = $_FILES['image'];

if ($file['error'] !== UPLOAD_ERR_OK) {
    header('Location: index.php?error=upload');
    exit;
}

if (!isValidImageType($file)) {
    header('Location: index.php?error=type');
    exit;
}

if (!isValidFileSize($file, 5242880)) {
    header('Location: index.php?error=size');
    exit;
}

$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$newFileName = uniqid('img_', true) . '.' . $extension;

$uploadDir = 'uploads/';
$thumbnailDir = 'uploads/thumbnails/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}
if (!is_dir($thumbnailDir)) {
    mkdir($thumbnailDir, 0755, true);
}

$uploadPath = $uploadDir . $newFileName;

// Перемещаем загруженный файл
if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
    $thumbnailPath = $thumbnailDir . 'thumb_' . $newFileName;
    createThumbnail($uploadPath, $thumbnailPath, 200, 200);

    header('Location: index.php?uploaded=1');
} else {
    header('Location: index.php?error=upload');
}
?>