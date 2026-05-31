<?php

 // Построение галереи из папки с изображениями

function buildGallery($directory) {
    $files = [];
    
    if (is_dir($directory)) {
        if ($handle = opendir($directory)) {
            while (false !== ($entry = readdir($handle))) {
                // Исключаем системные файлы и папки
                if ($entry != "." && $entry != ".." && $entry != "thumbnails") {
                    // Проверяем, что это изображение
                    $extension = strtolower(pathinfo($entry, PATHINFO_EXTENSION));
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                    
                    if (in_array($extension, $allowedExtensions)) {
                        $files[] = $entry;
                    }
                }
            }
            closedir($handle);
        }
    }
    
    sort($files);
    
    return $files;
}


//Проверка типа файла

function isValidImageType($file) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
    $detectedType = finfo_file($fileInfo, $file['tmp_name']);
    finfo_close($fileInfo);
    
    return in_array($detectedType, $allowedTypes) && in_array($file['type'], $allowedTypes);
}



function isValidFileSize($file, $maxSize = 5242880) { // 5MB по умолчанию
    return $file['size'] <= $maxSize;
}


 //Создание миниатюры изображения

function createThumbnail($sourcePath, $destPath, $maxWidth = 200, $maxHeight = 200) {

    list($originalWidth, $originalHeight, $imageType) = getimagesize($sourcePath);
    
    // Вычисляем новые размеры с сохранением пропорций
    $ratio = min($maxWidth / $originalWidth, $maxHeight / $originalHeight);
    $newWidth = round($originalWidth * $ratio);
    $newHeight = round($originalHeight * $ratio);

    $thumb = imagecreatetruecolor($newWidth, $newHeight);
    
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $source = imagecreatefromjpeg($sourcePath);
            break;
        case IMAGETYPE_PNG:
            $source = imagecreatefrompng($sourcePath);
            // Сохраняем прозрачность для PNG
            imagealphablending($thumb, false);
            imagesavealpha($thumb, true);
            break;
        case IMAGETYPE_GIF:
            $source = imagecreatefromgif($sourcePath);
            break;
        default:
            return false;
    }
    
    imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
    
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            imagejpeg($thumb, $destPath, 85);
            break;
        case IMAGETYPE_PNG:
            imagepng($thumb, $destPath, 8);
            break;
        case IMAGETYPE_GIF:
            imagegif($thumb, $destPath);
            break;
    }
    
    // Освобождаем память
    imagedestroy($source);
    imagedestroy($thumb);
    
    return true;
}

 //Логирование запросов (задание 4*)

function logRequest() {
    $logFile = 'log.txt';
    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'];
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $requestUri = $_SERVER['REQUEST_URI'];
    
    $logEntry = "[{$timestamp}] IP: {$ip} | User Agent: {$userAgent} | Request: {$requestUri}\n";
    
    // Добавляем запись в лог
    file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    
    // Проверяем количество записей и ротируем файл (задание 5*)
    rotateLogFile($logFile);
}


// Ротация лог-файла (задание 5*)

function rotateLogFile($logFile) {
    if (!file_exists($logFile)) {
        return;
    }
    

    $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $lineCount = count($lines);
    
    // Если записей 10 или больше, пересохраняем файл
    if ($lineCount >= 10) {
        // Находим следующий номер для архивного файла
        $archiveNumber = 0;
        do {
            $archiveFile = "log{$archiveNumber}.txt";
            $archiveNumber++;
        } while (file_exists($archiveFile));
        
        // Переименовываем текущий лог-файл
        rename($logFile, $archiveFile);
        
        // Создаем новый пустой лог-файл
        file_put_contents($logFile, "=== Архив создан: {$archiveFile} ===\n");
    }
}
?>