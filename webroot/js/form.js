// submit form plugin
;(function( $, window, undefined ) {

    $.fn.antiSpam = function() {
        this.submit(function(){
            var $form = $(this);

            // resettiamo tutto al momento del submit
            $form.find('.error-message').remove();
            $form.find('.form-error').removeClass('form-error');
            $form.find('div.error').removeClass('error');
            $form.find('#token').remove();

            $.ajax({
                cache: false,
                async:   false,
                dataType : 'json',
                url:   '/magic/yoda',
                success: function(result) {
                    $('<input>').attr({
                        type: 'hidden',
                        id: 'token',
                        name: result
                    }).appendTo($form);
                }
            });

            var $formData = new FormData(this);

            $.ajax({
                cache: false,
                async:   false,
                type : 'POST',
                dataType : 'json',
                url:   $form.attr('action'),
                data : $formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    var result = response;

                    if(response.errors) {
                        for(var field in response.errors) {
                            var field_errors = response.errors[field];
                            var field_message = field_errors[Object.keys(field_errors)[0]];

                            if (typeof field_message === 'string' || field_message instanceof String) {
                                $form.find('*[name="' + field + '"]')
                                    .addClass('form-error')
                                    .closest('div')
                                    .addClass('error')
                                    .append('<div class="error-message">' + field_message + '</div>');
                            }
                        }
                    }
                    if(result['sent'] == 1) {
                        //gtag('event', 'generate_lead', {'event_category': 'Contatto'}); // nuovo GTAG - da abilitare per tracciamento Google Analytics
                        //if (typeof fbq !== 'undefined') { fbq('track', 'Lead'); }
                        $form[0].reset();
                    }

                    if(result['spam']) {
                        $form.find('input[type="submit"]').attr('disabled', 'disabled');
                        setTimeout(function(){ $form.find('input[type="submit"]').removeAttr('disabled'); }, 1500);
                    }
                    if(result['errors']) {
                        for(var field in result['errors']) {
                            var field_msg = result['errors'][field];
                            $form.find('*[name$="[' + field + ']"]')
                                    .addClass('form-error')
                                    .closest('div')
                                    .addClass('error')
                                    .append('<div class="error-message">' + field_msg + '</div>');
                        }
                    }
                    getFlashMessage();
                }
            });

            return false;

        });

    };

    // autoload
    $('.antispam').antiSpam();

})( jQuery, window );
