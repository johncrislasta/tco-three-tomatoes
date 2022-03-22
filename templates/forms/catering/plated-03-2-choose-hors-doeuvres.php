<?php
$meal_title = sanitize_title( $meal['name'] );
?>

<h3 class="hors_doeuvres-choice-header">Choice of <?php echo $meal['allowed_number_of_hors_doeuvres'] ?> Hors D'oeuvres</h3>
<div class="hors_doeuvres-choices-container choices" data-limit="<?php echo $meal['allowed_number_of_hors_doeuvres'] ?>" data-price="<?php echo $meal['hors_doeuvres_price'] ?>">
    <?php
    $field_type = $meal['allowed_number_of_hors_doeuvres'] > 1 ? 'checkbox' : 'radio';
    foreach( $meal['hors_doeuvres_choices'] as $hors_doeuvres ):
        $hors_doeuvres_title = sanitize_title( $hors_doeuvres['name'] );
        ?>

        <div class="hors_doeuvres-item" data-name="<?php echo $hors_doeuvres['name'] ?>" data-price="<?php echo $hors_doeuvres['price'] ?>" data-id="<?php echo $hors_doeuvres_title ?>">
            <input type="<?php echo $field_type ?>" value="<?php echo $hors_doeuvres['name'] ?>" name="plated_meal_hors_doeuvres" id='<?php echo $hors_doeuvres_title ?>'/>

            <label for="<?php echo $hors_doeuvres_title ?>">
                <span class="hors_doeuvres-name">
                    <?php echo $hors_doeuvres['name'] ?>
                </span>

                <?php if( $hors_doeuvres['price'] > 0 ): ?>
                <span class="hors_doeuvres-price">
                    + $ <?php echo $hors_doeuvres['price'] ?>
                </span>
                <?php endif ?>

                <span class="hors_doeuvres-image">
                    <img src="<?php echo $hors_doeuvres['image'] ?>"/>
                </span>
                <span class="hors_doeuvres-description">
                    <?php echo $hors_doeuvres['description'] ?>
                </span>
            </label>
        </div>

    <?php
    endforeach;
    ?>
</div>
