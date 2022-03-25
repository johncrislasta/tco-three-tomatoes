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

        <select name="hour"" data-id="<?php echo $secondary_question_slug ?>">
            <option>00</option>
            <?php for( $i = 1; $i <= 12; $i++ ): ?>
                <option><?php echo str_pad( $i, 2, '0', STR_PAD_LEFT )?></option>
            <?php endfor; ?>
        </select>
        <select name="minute"" data-id="<?php echo $secondary_question_slug ?>">
            <?php for( $i = 0; $i < 60; $i+=5 ): ?>
                <option><?php echo str_pad( $i, 2, '0', STR_PAD_LEFT )?></option>
            <?php endfor; ?>
        </select>
        <select name="ampm" data-id="<?php echo $secondary_question_slug ?>">
            <option>AM</option>
            <option>PM</option>
        </select>
    </div>
</div>

<?php
echo $after_content_html
?>
