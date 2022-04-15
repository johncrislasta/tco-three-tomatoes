<div class="sliding-form <?php echo $classes ?? '' ?> " id="<?php echo $form_id ?>"
    <?php foreach($data as $key=>$value): ?>
        data-<?php echo $key ?>="<?php echo $value?>"
    <?php endforeach; ?>
>

    <?php
    $slide_count = 1;
    foreach( $slides as $slide ):
        $classes = $slide_count == 1 ? 'active slide-in-right' : '';
        $classes .= $slide['classes'] ?? '';
        ?>
        <div class="slide <?php echo $classes ?>" id="<?php echo $slide['id'] ?>">
            <h1 class="slide-header"><?php echo $slide['header'] ?></h1>
            <div class="slide-content"><?php echo $slide['content'] ?></div>
        </div>
    <?php
    $slide_count++;
    endforeach; ?>

    <nav class="slider-nav-buttons">
        <div class="slide-prev slider-nav-button">Prev</div>
        <div class="slide-next slider-nav-button">Next</div>
    </nav>
</div>