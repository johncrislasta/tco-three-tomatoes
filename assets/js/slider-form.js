jQuery(function($){

    const $slider_prev = $('.slider-nav-button.slide-prev');
    const $slider_next = $('.slider-nav-button.slide-next');

    let $active_slide  = $('.slide.active');

    $slider_next.click(function(){
        if( $(this).is('[disabled]') ) return false;

        let $old_active_slide = $active_slide;
        let $new_active_slide = $old_active_slide.attr('class', 'slide slide-out-left')
            .next('.slide').attr('class', 'slide active slide-in-right');

        $active_slide = $new_active_slide;

        // check if current slide is the last slide
        if( $new_active_slide.next('.slide').length === 0 )
            $new_active_slide.parent().addClass('last-slide');
        else
            $new_active_slide.parent().removeClass('last-slide');
    });

    $slider_prev.click(function(){
        if( $(this).is('[disabled]') ) return false;

        let $old_active_slide = $active_slide;
        let $new_active_slide = $old_active_slide.attr('class', 'slide slide-out-right')
            .prev('.slide').attr('class', 'slide active slide-in-left');

        $active_slide = $new_active_slide;
    });

    function slider_form_next_slide() {
        $slider_next.click();
    }

    function slider_form_prev_slide() {
        $slider_prev.click();
    }
});

