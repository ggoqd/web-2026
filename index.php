<?php
const TEMPLATES_DIR = __DIR__ . '/src/templates/';
const LAYOUTS_DIR   = __DIR__ . '/src/layouts/';

$page = $_GET['page'] ?? 'index';
$params = [];

switch ($page) {
    case 'index':
        $params['title'] = 'Главная';
        $params['content'] = '<p>Добро пожаловать! Выберите задание в меню.</p>';
        break;

    case 'task1':
        $params['title'] = 'Задание 1: do…while';
        $params['content'] = renderTemplate('task1');
        break;

    case 'task2':
        $params['title'] = 'Задание 2: Области и города';
        $params['content'] = renderTemplate('task2');
        break;

    case 'task3':
        $params['title'] = 'Задание 3: Транслитерация';
        $params['content'] = renderTemplate('task3');
        break;

    case 'task4':
        $params['title'] = 'Задание 4: Простое меню (цикл)';
        $params['content'] = renderTemplate('task4');
        break;

    case 'task5':
        $params['title'] = 'Задание 5: Вложенное меню (рекурсия)';
        $params['content'] = renderTemplate('task5');
        break;

    case 'task6':
        $params['title'] = 'Задание 6: Города на букву "К"';
        $params['content'] = renderTemplate('task6');
        break;

    default:
        http_response_code(404);
        echo "404 страница не найдена";
        exit;
}

function getRegions(): array
{
    return [
        'Московская область' => ['Москва', 'Зеленоград', 'Клин'],
        'Ленинградская область' => ['Санкт-Петербург', 'Всеволожск', 'Павловск', 'Кронштадт'],
        'Рязанская область' => ['Рязань', 'Касимов', 'Скопин', 'Сасово'],
        'Свердловская область' => ['Екатеринбург', 'Нижний Тагил', 'Каменск-Уральский'],
    ];
}

function getTranslitMap(): array
{
    return [
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd',
        'е' => 'e', 'ё' => 'yo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i',
        'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
        'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
        'у' => 'u', 'ф' => 'f', 'х' => 'kh', 'ц' => 'ts', 'ч' => 'ch',
        'ш' => 'sh', 'щ' => 'shch', 'ъ' => '`', 'ы' => 'y', 'ь' => '`',
        'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
    ];
}

function getMenu(): array
{
    return [
        ['title' => 'Главная', 'page' => 'index'],
        ['title' => 'Задание 1', 'page' => 'task1'],
        ['title' => 'Задание 2', 'page' => 'task2'],
        ['title' => 'Задание 3', 'page' => 'task3'],
        ['title' => 'Задание 4', 'page' => 'task4'],
        ['title' => 'Задание 5', 'page' => 'task5'],
        ['title' => 'Задание 6', 'page' => 'task6'],
    ];
}

function getNestedMenu(): array
{
    return [
        ['title' => 'Главная', 'page' => 'index'],
        ['title' => 'Задания', 'children' => [
            ['title' => 'do…while', 'page' => 'task1'],
            ['title' => 'Области', 'page' => 'task2'],
            ['title' => 'Транслит', 'page' => 'task3'],
            ['title' => 'Города на К', 'page' => 'task6'],
        ]],
        ['title' => 'Меню', 'children' => [
            ['title' => 'Простое', 'page' => 'task4'],
            ['title' => 'Вложенное', 'page' => 'task5'],
        ]],
    ];
}

function doWhileNumbers(): string
{
    $i = 0;
    $result = '';
    do {
        if ($i == 0) {
            $result .= "$i – это ноль.<br>";
        } elseif ($i % 2 == 0) {
            $result .= "$i – чётное число.<br>";
        } else {
            $result .= "$i – нечётное число.<br>";
        }
        $i++;
    } while ($i <= 10);
    return $result;
}

function transliterate($str, $map): string
{
    $result = '';
    $str = mb_strtolower($str);
    $len = mb_strlen($str);
    for ($i = 0; $i < $len; $i++) {
        $char = mb_substr($str, $i, 1);
        $result .= $map[$char] ?? $char;
    }
    return $result;
}

function getCitiesStartingWithK($regions): string
{
    $result = '';
    foreach ($regions as $region => $cities) {
        $kCities = [];
        foreach ($cities as $city) {
            if (mb_substr($city, 0, 1) == 'К') {
                $kCities[] = $city;
            }
        }
        if (!empty($kCities)) {
            $result .= "$region: " . implode(', ', $kCities) . ".<br>";
        }
    }
    return $result;
}

function buildMenu($menu): string
{
    $html = '<ul>';
    foreach ($menu as $item) {
        if (isset($item['children'])) {
            $html .= '<li>' . $item['title'];
            $html .= buildMenu($item['children']);
            $html .= '</li>';
        } else {
            $html .= '<li><a href="?page=' . $item['page'] . '">' . $item['title'] . '</a></li>';
        }
    }
    $html .= '</ul>';
    return $html;
}

function renderTemplate($template, $params = []): string
{
    $file = TEMPLATES_DIR . $template . '.php';
    if (!file_exists($file)) {
        $file = LAYOUTS_DIR . $template . '.php';
    }
    if (!file_exists($file)) {
        return "Template not found: $template";
    }
    extract($params);
    ob_start();
    include $file;
    return ob_get_clean();
}

function render($page, $params = []): string
{
    $layoutParams = [
        'title'   => $params['title'] ?? '',
        'menu'    => renderTemplate('menu', ['menus' => getMenu()]),
        'content' => $params['content'] ?? renderTemplate($page, $params),
    ];
    return renderTemplate('main', $layoutParams);
}

echo render($page, $params);