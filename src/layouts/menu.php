<?php
/**
 * @var array $menus
 */
?>
<ul>
    <?php foreach ($menus as $item): ?>
        <li><a href="?page=<?php echo $item['page']; ?>"><?php echo $item['title']; ?></a></li>
    <?php endforeach; ?>
</ul>