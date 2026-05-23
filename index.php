<?php
// Блок переменных в начале страницы
$pageTitle = "Моя персональная страница | PHP Lab";
$mainHeading = "Добро пожаловать на мой сайт!";
$authorName = "Пользователь";
$description = "Это главная страница, сгенерированная с помощью PHP";
$currentYear = date('Y');
$email = "your.email@example.com";
$phone = "+7 (999) 123-45-67";

// Функция для склонения времени
function getCorrectTimeFormat() {
    $hours = date('G');
    $minutes = date('i');
    
    function getHoursWord($hours) {
        $lastDigit = $hours % 10;
        $lastTwoDigits = $hours % 100;
        
        if ($lastTwoDigits >= 11 && $lastTwoDigits <= 14) {
            return 'часов';
        }
        
        switch ($lastDigit) {
            case 1: return 'час';
            case 2:
            case 3:
            case 4: return 'часа';
            default: return 'часов';
        }
    }
    
    function getMinutesWord($minutes) {
        $minutesInt = intval($minutes);
        $lastDigit = $minutesInt % 10;
        $lastTwoDigits = $minutesInt % 100;
        
        if ($lastTwoDigits >= 11 && $lastTwoDigits <= 14) {
            return 'минут';
        }
        
        switch ($lastDigit) {
            case 1: return 'минута';
            case 2:
            case 3:
            case 4: return 'минуты';
            default: return 'минут';
        }
    }
    
    $hoursWord = getHoursWord($hours);
    $minutesWord = getMinutesWord($minutes);
    
    return "$hours $hoursWord $minutes $minutesWord";
}

function getGreeting() {
    $hour = date('G');
    
    if ($hour >= 5 && $hour < 12) return "Доброе утро";
    elseif ($hour >= 12 && $hour < 17) return "Добрый день";
    elseif ($hour >= 17 && $hour < 23) return "Добрый вечер";
    else return "Доброй ночи";
}

$currentTimeFormatted = getCorrectTimeFormat();
$greeting = getGreeting();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1><?php echo $mainHeading; ?></h1>
        
        <div class="greeting">
            <?php echo $greeting . ", " . $authorName; ?>!
        </div>
        
        <div class="info-block">
            <div class="info-item">
                <span class="info-label">Описание:</span>
                <span class="info-value"><?php echo $description; ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Email:</span>
                <span class="info-value"><?php echo $email; ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Телефон:</span>
                <span class="info-value"><?php echo $phone; ?></span>
            </div>
        </div>
        
        <div class="time-block">
            <p>Текущее время:</p>
            <p class="time-display">
                <?php echo $currentTimeFormatted; ?>
            </p>
            <p class="date-display">
                Дата: <?php echo date('d.m.Y'); ?>
            </p>
        </div>
        
        <div class="footer">
            <p>© <?php echo $currentYear; ?> <?php echo $authorName; ?>. Все права защищены.</p>
            <p>Страница сгенерирована с помощью PHP <?php echo phpversion(); ?></p>
        </div>
    </div>
</body>
</html>