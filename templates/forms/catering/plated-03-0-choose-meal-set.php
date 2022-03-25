<?php
foreach( $plated_meals as $meal_index => $meal ):
    $meal_title = sanitize_title( $meal['name'] );
?>

<div class="meal-item" data-name="<?php echo $meal['name'] ?>"
     data-price="<?php echo $meal['price'] ?>"
     data-id="<?php echo $meal_title ?>"
     data-meal_index="<?php echo $meal_index?>">

    <input type="radio" value="<?php echo $meal['name'] ?>" name="plated_meal" id='<?php echo $meal_title ?>' data-price="<?php echo $meal['price'] ?>"/>

    <label for="<?php echo $meal_title ?>">
        <span class="meal-name">
            <?php echo $meal['name'] ?>
        </span>
        <span class="meal-price">
            $ <?php echo $meal['price'] ?>
        </span>
        <span class="meal-image">
            <img src="<?php echo $meal['image'] ?>"/>
        </span>
        <span class="meal-description">
            <?php echo $meal['description'] ?>
        </span>
    </label>

</div>

<?php
endforeach;
?>
