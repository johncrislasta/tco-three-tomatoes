<div class="meal-plated-selection-wrapper">
<?php
foreach( $plated_meals as $meal_index => $meal ):
    $meal_title = sanitize_title( $meal['name'] );
?>
<label for="<?php echo $meal_title ?>">
    <div class="meal-item" data-name="<?php echo $meal['name'] ?>"
         data-price="<?php echo $meal['price'] ?>"
         data-id="<?php echo $meal_title ?>"
         data-meal_index="<?php echo $meal_index?>">

        <input type="radio" value="<?php echo $meal['name'] ?>" name="plated_meal" id='<?php echo $meal_title ?>' data-price="<?php echo $meal['price'] ?>"/>

            <p class="meal-image">
                <img src="<?php echo $meal['image'] ?>"/>
            </p>
            <p class="meal-name">
                <?php echo $meal['name'] ?>
            </p>
            <p class="meal-price">
                Price: $<?php echo $meal['price'] ?>
            </p>
            <p class="meal-information">
                <span class="tc-modal-open">More Information</span>
            </p>

    </div>

    <dialog class="plated-modal-box" id="plated-modal">
        <i class="x-icon tc-modal-close" aria-hidden="true" data-x-icon-s=""></i>
        <div class="meal-more-info-wrapper">
            <div class="modal-meal-image">
                <span class="meal-image">
                    <img src="<?php echo $meal['image'] ?>"/>
                </span>
            </div>
            <div class="modal-meal-more-info">
                <p class="modal-meal-name">
                    <?php echo $meal['name'] ?>
                </p>
                <span class="meal-description">
                   <?php echo $meal['description'] ?>
                </span>
            </div>
        </div>
    </dialog>
</label>


<?php
endforeach;
?>
</div>
