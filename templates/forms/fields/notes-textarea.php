<?php
echo $before_content_html
?>

<div class="notes">
    <h2 class="question">
        <?php echo $question ?>
    </h2>

    <textarea name="plate-meal-addon-<?php echo $question_slug ?>" placeholder="<?php echo $placeholder ?>"></textarea>
</div>

<?php
echo $after_content_html
?>
