<?php
require_once 'functions.php';

// Логирование каждого запроса (задание 4*)
logRequest();

// Обработка загрузки файла
$message = '';
if (isset($_GET['uploaded'])) {
    $message = '<div class="success">Файл успешно загружен!</div>';
} elseif (isset($_GET['error'])) {
    $errorMessages = [
        'size' => 'Ошибка: файл слишком большой. Максимальный размер 5MB.',
        'type' => 'Ошибка: разрешены только файлы JPG, JPEG, PNG и GIF.',
        'upload' => 'Ошибка при загрузке файла.',
        'no_file' => 'Ошибка: файл не выбран.'
    ];
    $errorType = $_GET['error'];
    $message = '<div class="error">' . ($errorMessages[$errorType] ?? 'Неизвестная ошибка.') . '</div>';
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Фотогалерея</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 2.5em;
        }
        
        .upload-form {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        .upload-form h2 {
            color: #495057;
            margin-bottom: 15px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        input[type="file"] {
            padding: 10px;
            border: 2px dashed #ddd;
            border-radius: 5px;
            width: 100%;
            cursor: pointer;
        }
        
        button {
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        button:hover {
            background: #5a67d8;
        }
        
        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
        
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }
        
        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
        }
        
        .gallery-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        }
        
        .gallery-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            display: block;
        }
        
        .gallery-item .caption {
            padding: 10px;
            background: white;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
        
        .info {
            text-align: center;
            color: #666;
            margin-top: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📸 Фотогалерея</h1>
        
        <?php echo $message; ?>
        
        <div class="upload-form">
            <h2>Загрузить новое изображение</h2>
            <form action="upload.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="file" name="image" accept="image/jpeg,image/png,image/gif" required>
                </div>
                <button type="submit">Загрузить изображение</button>
            </form>
        </div>
        
        <div class="gallery">
            <?php
            // Построение галереи из папки uploads (задание 2)
            $galleryPath = 'uploads/';
            if (is_dir($galleryPath)) {
                $images = buildGallery($galleryPath);
                foreach ($images as $image) {
                    if ($image !== 'thumbnails' && $image !== '.' && $image !== '..') {
                        $thumbnailPath = $galleryPath . 'thumbnails/thumb_' . $image;
                        $fullImagePath = $galleryPath . $image;
                        
                        // Если есть миниатюра, используем её, иначе показываем оригинал
                        $displayPath = file_exists($thumbnailPath) ? $thumbnailPath : $fullImagePath;
                        ?>
                        <div class="gallery-item">
                            <a href="<?php echo htmlspecialchars($fullImagePath); ?>" target="_blank">
                                <img src="<?php echo htmlspecialchars($displayPath); ?>" 
                                     alt="<?php echo htmlspecialchars($image); ?>"
                                     loading="lazy">
                                <div class="caption"><?php echo htmlspecialchars($image); ?></div>
                            </a>
                        </div>
                        <?php
                    }
                }
            }
            ?>
        </div>
        
        <div class="info">
            <?php 
            if (isset($images)) {
                echo 'Всего изображений: ' . (count($images) - 1); // -1 для исключения папки thumbnails
            }
            ?>
        </div>
    </div>
</body>
</html>