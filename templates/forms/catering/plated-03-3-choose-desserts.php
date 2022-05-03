<?php
if($meal):

$meal_title = sanitize_title( $meal['name'] );
?>

<h3 class="dessert-choice-header">Choice of <?php echo $meal['allowed_number_of_desserts'] ?> Dessert</h3>
<div class="dessert-choices-container choices" data-limit="<?php echo $meal['allowed_number_of_desserts'] ?>"  data-price="<?php echo $meal['desserts_price'] ?>">
    <?php
    $field_type = $meal['allowed_number_of_desserts'] > 1 ? 'checkbox' : 'radio';
    foreach( $meal['dessert_choices'] as $dessert ):
        $dessert_title = sanitize_title( $dessert['name'] );
        ?>
        <div class="dessert-item" data-name="<?php echo $dessert['name'] ?>" data-price="<?php echo $dessert['price'] ?>" data-id="<?php echo $dessert_title ?>">
            <input type="<?php echo $field_type ?>" value="<?php echo $dessert['name'] ?>" name="plated_meal_dessert" id='<?php echo $dessert_title ?>'/>

            <label for="<?php echo $dessert_title ?>">

                <span class="dessert-image">
                    <img src="<?php echo $dessert['image'] ?>"/>
                </span>

                <span class="dessert-name">
                    <?php echo $dessert['name'] ?>
                </span>

                <?php if( $dessert['price'] > 0 ): ?>
                    <span class="dessert-price">
                    (+ $ <?php echo $dessert['price'] ?>)
                </span>
                <?php endif ?>

                <span class="dessert-description">
                    <?php echo $dessert['description'] ?>
                </span>
            </label>
        </div>

    <?php
    endforeach;
    ?>
</div>

<?php
else:
echo "<h3>Please select a Meal Set from the previous slide. Thanks! </h3>";
endif;
?>