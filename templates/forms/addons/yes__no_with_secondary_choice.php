<?php
echo $before_content_html
?>

<div class="addon">
    <h2 class="question">
        <?php echo $question ?>
    </h2>

    <div class="answers-container">
        <label class="answer">
            <input type="radio" name="<?php echo $question_slug ?>" value="Yes" data-price="<?php echo $price_for_yes ?? 0 ?>" />
            <?php if( isset( $image_for_yes ) && $image_for_yes): ?>
                <img src="<?php echo $imgsrc_for_yes ?>" />
            <?php endif ?>
            <span class="label">Yes <?php echo $price_for_yes ? '( ' . $currency . $price_for_yes . ' )' : '' ?></span>

        </label>

        <label class="answer">
            <input type="radio" name="<?php echo $question_slug ?>" value="No" data-price="<?php echo $price_for_no ?? 0 ?>" />
            <?php if( isset( $image_for_no ) && $image_for_no ): ?>
                <img src="<?php echo $imgsrc_for_no ?>" />
            <?php endif ?>
            <span class="label">No  <?php echo $price_for_no ? '( ' . $currency . $price_for_no . ' )' : '' ?></span>
        </label>
    </div>

    <?php
    echo $before_secondary_content_html
    ?>

    <div class="secondary-question" data-show_if="<?php echo $show_secondary_if_answer_equals ?>" style="display:none">
        <h3 class="question">
            <?php echo $secondary_question ?>
        </h3>
        <div class="answers-container">
            <?php foreach ($choices as $choice): ?>
                <label class="answer">
                    <input type="radio" name="<?php echo $secondary_question_slug ?>"
                           value="<?php echo isset($choice['value']) && $choice['value'] !== '' ? $choice['value'] : $choice['text'] ?>"
                           data-price="<?php echo $choice['price'] ?? 0 ?>"/>
                    <?php if (isset($choice['imnage'])): ?>
                        <img src="<?php echo $choice['imgsrc'] ?>"/>
                    <?php endif ?>
                    <span class="label"><?php echo $choice['text'] ?><?php echo $choice['price'] ? '( ' . $currency . $choice['price'] . ' )' : '' ?></span>
                </label>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php
echo $after_content_html
?>
