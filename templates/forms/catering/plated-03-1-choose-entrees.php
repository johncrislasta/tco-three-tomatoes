<?php
    $meal_title = sanitize_title( $meal['name'] );
?>
<!--Number of entrees is decided by the number of plates added for each entree -->
<!--<h3 class="entree-choice-header">Choice of --><?php //echo $meal['allowed_number_of_entrees'] ?><!-- Entrée</h3>-->
<div class="entree-choices-container choices" data-limit="<?php echo $meal['allowed_number_of_entrees'] ?>" data-price="<?php echo $meal['entrees_price'] ?>">
    <?php
    $field_type = $meal['allowed_number_of_entrees'] > 1 ? 'checkbox' : 'radio';
    foreach( $meal['entree_choices'] as $entree ):
        $entree_title = sanitize_title( $entree['name'] );
        ?>

        <div class="entree-item" data-name="<?php echo $entree['name'] ?>" data-price="<?php echo $entree['price'] ?>" data-id="<?php echo $entree_title ?>">
            <input type="<?php echo $field_type ?>" value="<?php echo $entree['name'] ?>" name="plated_meal_entree" id='<?php echo $entree_title ?>'/>

            <label for="<?php echo $entree_title ?>">
                <span class="entree-name">
                    <?php echo $entree['name'] ?>
                </span>
                <?php if( $entree['price'] > 0 ): ?>
                <span class="entree-price">
                    + $ <?php echo $entree['price'] ?>
                </span>
                <?php endif ?>
                <span class="entree-image">
                    <img src="<?php echo $entree['image'] ?>"/>
                </span>
                <span class="entree-description">
                    <?php echo $entree['description'] ?>
                </span>
            </label>
            <label>
                Number of plates
                <input type="number" class="entree-number-of-guest-plates" name="num_of_guest_plates_for_<?php echo $entree_title ?>" min="0" />
            </label>
        </div>

    <?php
    endforeach;
    ?>
</div>
