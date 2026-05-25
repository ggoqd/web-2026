<div class="task-block">
    <h2>Задание 2: Города по областям</h2>
    <?php $regions = getRegions(); ?>
    <?php foreach ($regions as $region => $cities): ?>
        <p><strong><?php echo $region; ?>:</strong></p>
        <ul>
            <?php foreach ($cities as $city): ?>
                <li><?php echo $city; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endforeach; ?>
</div>