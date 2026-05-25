<div class="task-block">
    <h2>Задание 4: Простое меню (цикл)</h2>
    <?php $simpleMenu = getMenu(); ?>
    <div class="task-menu">
        <ul>
            <?php foreach ($simpleMenu as $item): ?>
                <li><a href="?page=<?php echo $item['page']; ?>"><?php echo $item['title']; ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>