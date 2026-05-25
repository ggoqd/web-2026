<div class="task-block">
    <h2>Задание 3: Транслитерация</h2>
    <?php $map = getTranslitMap(); ?>
    <p>Привет мир → <?php echo transliterate('Привет мир', $map); ?></p>
    <p>Съешь ещё этих мягких французских булок → <?php echo transliterate('Съешь ещё этих мягких французских булок', $map); ?></p>
</div>