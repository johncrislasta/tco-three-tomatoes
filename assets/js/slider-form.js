jQuery(function($){

    const $slider_prev = $('.slider-nav-button.slide-prev');
    const $slider_next = $('.slider-nav-button.slide-next');
    const $slider_quick_nav = $('.slider-quick-nav');

    let $active_slide  = $('.slide.active');

    $slider_next.click(function(){
        if( $(this).is('[disabled]') ) return false;

        $slider_quick_nav.val(
            $active_slide.next('.slide')
                .attr('id')
        ).change();

        // check if current slide is the last slide
        if( $new_active_slide.next('.slide').length === 0 )
            $new_active_slide.parent().addClass('last-slide');
        else
            $new_active_slide.parent().removeClass('last-slide');
    });

    $slider_prev.click(function(){
        if( $(this).is('[disabled]') ) return false;

        $slider_quick_nav.val(
            $active_slide.prev('.slide')
                .attr('id')
        ).change();


    });

    function slider_form_next_slide() {
        $slider_next.click();
    }

    function slider_form_prev_slide() {
        $slider_prev.click();
    }

    function switch_to_slide(from, to, direction) {
         if( direction === 'right' ) {
             from.attr('class', 'slide slide-out-left');
             to.attr('class', 'slide active slide-in-right');
         }
         else if ( direction === 'left' ) {
             from.attr('class', 'slide slide-out-right');
             to.attr('class', 'slide active slide-in-left');
         }

        $active_slide = to;

    }

    // Implement quick navigation
    $slider_quick_nav.change(function () {

        let $old_active_slide = $active_slide;
        let $new_active_slide = $('#' + $(this).val() );

        let direction = 'left';

        if( $old_active_slide.isBefore( '#' + $(this).val() ) )
            direction = 'right';

        switch_to_slide( $old_active_slide, $new_active_slide, direction );
    });

    $.fn.isAfter = function(sel){
        return this.prevAll().filter(sel).length !== 0;
    };

    $.fn.isBefore= function(sel){
        return this.nextAll().filter(sel).length !== 0;
    };
});

