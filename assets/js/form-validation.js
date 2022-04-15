jQuery(function($){

    const error_messages = {
        'required' : 'This field is required'
    };

    let $error_msg = $('<div class="validation-error-message">');

    $('[data-validation="inline"]').find('[required]').blur(function(){

        let $field = $(this);

        let $required_error_msg = prepare_error_message_container($field, error_messages.required);

        $field.removeClass('validation-failed' );

        if( $field.val() === '') {
            $required_error_msg.show();
            $field.addClass('validation-failed' );
        }
    });

    $('[data-validation-trigger]').each(function() {

        let $form = $(this);
        $($form.data('validation-trigger')).click( validate_form );

    });

    function validate_form() {

    }

    function prepare_error_message_container( $field, message ) {

        // Check if error message has already been appended
        let $field_error_msg = $field.next('.validation-error-message' );

        if( $field_error_msg.length === 0 )
            $field_error_msg = $error_msg.clone();

        $field_error_msg.text( message );

        $field_error_msg.hide();
        $field.after($field_error_msg);

        return $field_error_msg;
    }

});