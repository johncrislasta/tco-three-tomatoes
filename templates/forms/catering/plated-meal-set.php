<?php
foreach( $plated_meals as $meal ):
    $meal_title = sanitize_title( $meal['name'] );
?>

<div class="meal-item" data-name="<?php echo $meal['name'] ?>" data-price="<?php echo $meal['price'] ?>" data-id="<?php echo $meal_title ?>">
    <input type="radio" value="<?php echo $meal['name'] ?>" name="plated_meal" id='<?php echo $meal_title ?>'/>

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

    <h3 class="entree-choice-header">Choice of <?php echo $meal['allowed_number_of_entrees'] ?> Entrée</h3>
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
            </div>

        <?php
        endforeach;
        ?>
    </div>

    <h3 class="hors_doueuvres-choice-header">Choice of <?php echo $meal['allowed_number_of_hors_doueuvres'] ?> Hors D'oeuvres</h3>
    <div class="hors_doueuvres-choices-container choices" data-limit="<?php echo $meal['allowed_number_of_hors_doueuvres'] ?>" data-price="<?php echo $meal['hors_doueuvres_price'] ?>">
        <?php
        $field_type = $meal['allowed_number_of_hors_doueuvres'] > 1 ? 'checkbox' : 'radio';
        foreach( $meal['hors_doueuvres_choices'] as $hors_doueuvres ):
            $hors_doueuvres_title = sanitize_title( $hors_doueuvres['name'] );
            ?>

            <div class="hors_doueuvres-item" data-name="<?php echo $hors_doueuvres['name'] ?>" data-price="<?php echo $hors_doueuvres['price'] ?>" data-id="<?php echo $hors_doueuvres_title ?>">
                <input type="<?php echo $field_type ?>" value="<?php echo $hors_doueuvres['name'] ?>" name="plated_meal_hors_doueuvres" id='<?php echo $hors_doueuvres_title ?>'/>

                <label for="<?php echo $hors_doueuvres_title ?>">
                    <span class="hors_doueuvres-name">
                        <?php echo $hors_doueuvres['name'] ?>
                    </span>

                    <?php if( $hors_doueuvres['price'] > 0 ): ?>
                    <span class="hors_doueuvres-price">
                        + $ <?php echo $hors_doueuvres['price'] ?>
                    </span>
                    <?php endif ?>

                    <span class="hors_doueuvres-image">
                        <img src="<?php echo $hors_doueuvres['image'] ?>"/>
                    </span>
                    <span class="hors_doueuvres-description">
                        <?php echo $hors_doueuvres['description'] ?>
                    </span>
                </label>
            </div>

        <?php
        endforeach;
        ?>
    </div>

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
                    <span class="dessert-name">
                        <?php echo $dessert['name'] ?>
                    </span>

                    <?php if( $hors_doueuvres['price'] > 0 ): ?>
                    <span class="dessert-price">
                        + $ <?php echo $dessert['price'] ?>
                    </span>
                    <?php endif ?>

                    <span class="dessert-image">
                        <img src="<?php echo $dessert['image'] ?>"/>
                    </span>
                    <span class="dessert-description">
                        <?php echo $dessert['description'] ?>
                    </span>
                </label>
            </div>

        <?php
        endforeach;
        ?>
    </div>

</div>

<?php
endforeach;
?>
