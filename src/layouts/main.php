<?php
/**
 * @var string $title
 * @var string $menu
 * @var string $content
 */
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="src/styles/style.css">
</head>
<body>
    <div class="container">
        <h1><?php echo $title; ?></h1>
        <div class="menu-block">
            <?php echo $menu; ?>
        </div>
        <div class="content">
            <?php echo $content; ?>
        </div>
    </div>
</body>
</html>