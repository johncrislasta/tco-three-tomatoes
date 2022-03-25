<?php
echo $before_content_html
?>

<div class="addon">
    <h2 class="question">
        <?php echo $question ?>
    </h2>

    <label class="answer">
        <input type="radio" name="<?php echo $question_slug ?>" value="Yes" data-price="<?php echo $price_for_yes ?? 0 ?>" />
        <?php if( isset( $image_for_yes ) ): ?>
        <img src="<?php echo $imgsrc_for_yes ?>" />
        <?php endif ?>
        <span class="label">Yes <?php echo $price_for_yes ? '( ' . $currency . $price_for_yes . ' )' : '' ?></span>

    </label>

    <label class="answer">
        <input type="radio" name="<?php echo sanitize_title($question) ?>" value="No" data-price="<?php echo $price_for_no ?? 0 ?>" />
        <?php if( isset( $image_for_no ) ): ?>
        <img src="<?php echo $imgsrc_for_no ?>" />
        <?php endif ?>
        <span class="label">No  <?php echo $price_for_no ? '( ' . $currency . $price_for_no . ' )' : '' ?></span>

    </label>

    <?php
    echo $before_secondary_content_html
    ?>

    <div class="secondary-question" data-show_if="Yes" style="display:none">
        <h3 class="question">
            <?php echo $secondary_question ?>
        </h3>

        <textarea name="plate-meal-addon-<?php echo $secondary_question_slug ?>" placeholder="<?php echo $placeholder ?>"></textarea>

    </div>
</div>

<?php
echo $after_content_html
?>
