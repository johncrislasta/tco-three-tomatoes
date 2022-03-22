jQuery(function($){

    const $slider_prev = $('.slider-nav-button.slide-prev');
    const $slider_next = $('.slider-nav-button.slide-next');

    $slider_next.click(function(){
       let $old_active_slide = $('.slide.active');
       let $new_active_slide = $old_active_slide.attr('class', 'slide slide-out-left')
           .next('.slide').attr('class', 'slide active slide-in-right');
    });

    $slider_prev.click(function(){
       let $old_active_slide = $('.slide.active');
       let $new_active_slide = $old_active_slide.attr('class', 'slide slide-out-right')
           .prev('.slide').attr('class', 'slide active slide-in-left');
    });

    function slider_form_next_slide() {
        $slider_next.click();
    }

    function slider_form_prev_slide() {
        $slider_prev.click();
    }
});