<?php
$pageTitle = "Практика 17 | PHP";
$mainHeading = "Практическая работа №17";

// Задание 1
$a = 5;
$b = -3;

function task1($a, $b) {
    if ($a >= 0 && $b >= 0) {
        return "Разность: $a - $b = " . ($a - $b);
    } elseif ($a < 0 && $b < 0) {
        return "Произведение: $a * $b = " . ($a * $b);
    } else {
        return "Сумма: $a + $b = " . ($a + $b);
    }
}

$task1Result = task1($a, $b);

// Задание 2
$a2 = 7;

function task2($a) {
    $result = '';
    switch (true) {
        case ($a <= 0):  $result .= "0 1 2 3 4 5 6 7 8 9 10 11 12 13 14 15"; break;
        case ($a <= 1):  $result .= "1 2 3 4 5 6 7 8 9 10 11 12 13 14 15"; break;
        case ($a <= 2):  $result .= "2 3 4 5 6 7 8 9 10 11 12 13 14 15"; break;
        case ($a <= 3):  $result .= "3 4 5 6 7 8 9 10 11 12 13 14 15"; break;
        case ($a <= 4):  $result .= "4 5 6 7 8 9 10 11 12 13 14 15"; break;
        case ($a <= 5):  $result .= "5 6 7 8 9 10 11 12 13 14 15"; break;
        case ($a <= 6):  $result .= "6 7 8 9 10 11 12 13 14 15"; break;
        case ($a <= 7):  $result .= "7 8 9 10 11 12 13 14 15"; break;
        case ($a <= 8):  $result .= "8 9 10 11 12 13 14 15"; break;
        case ($a <= 9):  $result .= "9 10 11 12 13 14 15"; break;
        case ($a <= 10): $result .= "10 11 12 13 14 15"; break;
        case ($a <= 11): $result .= "11 12 13 14 15"; break;
        case ($a <= 12): $result .= "12 13 14 15"; break;
        case ($a <= 13): $result .= "13 14 15"; break;
        case ($a <= 14): $result .= "14 15"; break;
        case ($a <= 15): $result .= "15"; break;
        default: $result .= "Число вне диапазона [0..15]";
    }
    return $result;
}

$task2Result = task2($a2);

// Задание 3
function add($x, $y) {
    return $x + $y;
}

function subtract($x, $y) {
    return $x - $y;
}

function multiply($x, $y) {
    return $x * $y;
}

function divide($x, $y) {
    if ($y == 0) {
        return "Ошибка: деление на ноль";
    }
    return $x / $y;
}

// Задание 4
function mathOperation($arg1, $arg2, $operation) {
    switch ($operation) {
        case 'add': return add($arg1, $arg2);
        case 'subtract': return subtract($arg1, $arg2);
        case 'multiply': return multiply($arg1, $arg2);
        case 'divide': return divide($arg1, $arg2);
        default: return "Неизвестная операция";
    }
}

// Задание 5*
$year1 = date('Y');
$year2 = idate('Y');
$dt = new DateTime();
$year3 = $dt->format('Y');

// Задание 6*
function power($val, $pow) {
    if ($pow == 0) return 1;
    if ($pow < 0) return 1 / power($val, -$pow);
    return $val * power($val, $pow - 1);
}
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

        <div class="task-block">
            <h2>Задание 1</h2>
            <p>a = <?php echo $a; ?>, b = <?php echo $b; ?></p>
            <p>Результат: <?php echo $task1Result; ?></p>
        </div>

        <div class="task-block">
            <h2>Задание 2</h2>
            <p>a = <?php echo $a2; ?></p>
            <p>Числа от a до 15: <?php echo $task2Result; ?></p>
        </div>

        <div class="task-block">
            <h2>Задание 3</h2>
            <p>add(10, 5) = <?php echo add(10, 5); ?></p>
            <p>subtract(10, 5) = <?php echo subtract(10, 5); ?></p>
            <p>multiply(10, 5) = <?php echo multiply(10, 5); ?></p>
            <p>divide(10, 5) = <?php echo divide(10, 5); ?></p>
        </div>

        <div class="task-block">
            <h2>Задание 4</h2>
            <p>mathOperation(10, 5, 'add') = <?php echo mathOperation(10, 5, 'add'); ?></p>
            <p>mathOperation(10, 5, 'subtract') = <?php echo mathOperation(10, 5, 'subtract'); ?></p>
            <p>mathOperation(10, 5, 'multiply') = <?php echo mathOperation(10, 5, 'multiply'); ?></p>
            <p>mathOperation(10, 5, 'divide') = <?php echo mathOperation(10, 5, 'divide'); ?></p>
        </div>

        <div class="task-block">
            <h2>Задание 5*</h2>
            <p>Способ 1: <?php echo $year1; ?></p>
            <p>Способ 2: <?php echo $year2; ?></p>
            <p>Способ 3: <?php echo $year3; ?></p>
        </div>

        <div class="task-block">
            <h2>Задание 6*</h2>
            <p>2^8 = <?php echo power(2, 8); ?></p>
            <p>3^4 = <?php echo power(3, 4); ?></p>
            <p>5^0 = <?php echo power(5, 0); ?></p>
        </div>
    </div>
</body>
</html>