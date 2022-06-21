<div class="sliding-form <?php echo $classes ?? '' ?> " id="<?php echo $form_id ?>"
    <?php foreach($data as $key=>$value): ?>
        data-<?php echo $key ?>="<?php echo $value?>"
    <?php endforeach; ?>
>

    <?php
    $slide_count = 1;
    $slider_dots = '';
    foreach( $slides as $slide ):
        $classes = $slide_count == 1 ? 'active slide-in-right' : '';
        $classes .= $slide['classes'] ?? '';
        ?>
        <div class="slide <?php echo $classes ?>" id="<?php echo $slide['id'] ?>">
            <h1 class="slide-header"><?php echo $slide['header'] ?></h1>
            <div class="slide-content"><?php echo $slide['content'] ?></div>
        </div>
    <?php
        $slider_dots .= "<option class=\"slider-dot {$slide['classes']}\" title='{$slide['header']}' value='{$slide['id']}'>{$slide['header']}</option>";
        $slide_count++;
    endforeach; ?>

    <nav class="slider-nav-buttons">
        <div class="slide-prev slider-nav-button">Prev</div>

        <div class="slide-next slider-nav-button">Next</div>
    </nav>

    <select class="slider-quick-nav" title="Select slide to switch to">
        <option value="">Jump to slide:</option>
        <?php
        echo $slider_dots;
        ?>
    </select>
</div>