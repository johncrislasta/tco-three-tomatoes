<?php
echo $before_content_html
?>

<div class="addon">
    <h2 class="question">
        <?php echo $question ?>
    </h2>

    <div class="multiple-choice-answers">
        <?php foreach ( $choices as $choice ): ?>
            <label class="answer">
                <input type="radio" name="<?php echo $question_slug ?>" value="<?php echo isset($choice['value']) && $choice['value'] !== '' ? $choice['value'] : $choice['text'] ?>" data-price="<?php echo $choice['price'] ?? 0 ?>" />
                <?php if( isset( $choice['imnage'] ) ): ?>
                    <img src="<?php echo $choice['imgsrc'] ?>" />
                <?php endif ?>
                <span class="label"><?php echo $choice['text'] ?>  <?php echo $choice['price'] ? '( ' . $currency . $choice['price'] . ' )' : '' ?></span>
            </label>
        <?php endforeach; ?>
    </div>
</div>

<?php
echo $after_content_html
?>
