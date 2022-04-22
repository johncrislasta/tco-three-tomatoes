jQuery(function($){

    const error_messages = {
        'required' : 'This field is required'
    };

    let $error_msg = $('<div class="validation-error-message">');

    $('[data-validation="inline"]').on('blur', '[required]', function(){

        let $field = $(this);

        let is_field_valid = validate_required($field);

        // check if form has triggers to disable
        let $triggered_form = $field.closest('[data-validation-trigger]');
        if( $triggered_form.length === 0 ) return true;

        let triggers = $triggered_form.data('validation-trigger');

        if( is_field_valid )
            $(triggers).attr('disabled', null );
        else
            $(triggers).attr('disabled', 'disabled');

    });

    function validate_required($field) {

        let $required_error_msg = prepare_error_message_container($field, error_messages.required);

        $field.removeClass('validation-failed' );

        if( $field.val() === '') {
            $required_error_msg.show();
            $field.addClass('validation-failed' );
            return false;
        }
        return true;
    }

    $('[data-validation-trigger]').each(function() {

        let $form = $(this);
        let triggers = $form.data('validation-trigger');

        $form.on('mouseup', triggers, {form: $form, triggers: triggers}, validate_form );

    });

    function validate_form( event ) {
        let $form = event.data.form;
        let triggers = event.data.triggers;
        let validation_failed_fields = [];

        // validate required
        $form.find('[required]').each(function (){
            if( ! validate_required( $(this) ) )
                validation_failed_fields.push($(this).attr('name'));
        });

        console.log(validation_failed_fields);
        // check if there are failed validations
        if( validation_failed_fields.length > 0 ) {
            event.stopImmediatePropagation();
            $('[name=' + validation_failed_fields[0] + ']').focus();
            $(triggers).attr('disabled', 'disabled');
            return false;
        } else {
            $(triggers).attr('disabled', null);
            return true;
        }
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