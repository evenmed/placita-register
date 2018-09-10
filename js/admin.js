(function($) { $(function(){

    // Show input when user clicks "Edit"
    $('.edit-registry-field').click(function(e) {
        e.preventDefault();
        $(this).addClass("show-input");
    })

    // Registries export datetimepicker
    $(".registries_export_date").datetimepicker({
        disabledWeekDays: [1, 2, 3, 4],
        allowTimes:['7:30', '9:30', '11:30', '13:15', '13:30', '15:15'],
        format: 'Y/m/d H:i',
        scrollMonth: false,
        scrollTime: false,
        scrollInput: false,
        onSelectDate: function(ct, $this) {
            const day = ct.getDay();
            const times = [];
            switch (day){
                case 0: // Sunday
                    times.push('9:30', '11:30', '13:30')
                    break;
                case 5: // Friday
                    times.push('13:30')
                    break;
                case 6: // Saturday
                    times.push('7:30', '9:30', '11:30', '13:15', '15:15')
                    break;
            }

            $this.datetimepicker(
                'setOptions',
                {allowTimes: times}
            );
        },
        onSelectTime: function(ct, $this) {
            $this.blur();
        }
    });

    // Baptism date datetimepicker
    $(".input_baptism_date").datetimepicker({
        disabledWeekDays: [1, 2, 3, 4],
        allowTimes:['7:30', '9:30', '11:30', '13:15', '13:30', '15:15'],
        format: 'Y/m/d H:i',
        scrollMonth: false,
        scrollTime: false,
        scrollInput: false,
        onSelectDate: function(ct, $this) {
            const day = ct.getDay();
            const times = [];
            switch (day){
                case 0: // Sunday
                    times.push('9:30', '11:30', '13:30')
                    break;
                case 5: // Friday
                    times.push('13:30')
                    break;
                case 6: // Saturday
                    times.push('7:30', '9:30', '11:30', '13:15', '15:15')
                    break;
            }

            $this.datetimepicker(
                'setOptions',
                {allowTimes: times}
            );
        },
        onSelectTime: function(ct, $this) {
            $this.blur();
            registryUpdate($this);
        }
    });

    // Birth date datepicker
    $(".input_birthdate").datetimepicker({
        format: 'Y/m/d',
        timepicker: false,
        onSelectDate: function(ct, $this) {
            $this.blur();
            registryUpdate($this);
        }
    });

    $('.registry-update').keydown(function(e) {
        if(e.keyCode == 13) {
            e.preventDefault();
            $(this).blur();
            return false;
        }
    });

    $('.registry-update:not(.datetimepicker)')
        .change(function() {
            registryUpdate($(this));
        });

    function registryUpdate( $this ) {

        // Disable the input and show loading spinner
        $this
            .prop('disabled', true)
            .after( '<img class="loading-spinner" width=20 height=20 src="'+ server_data.loading_spinner_url +'" />' )
            .addClass( 'loading' );

        const registry = $this.attr('data-registry');
        const field = $this.attr('name');
        const value = $this.attr('type') !== 'checkbox' ? $this.val() : ($this.is(':checked') ? '1' : '0');
        $.post(
            ajaxurl,
            {
                action: 'update_registry',
                _wpnonce: server_data.update_registry_nonce,
                registry,
                field,
                value
            },
            function(r) {
                var editRegistryLink = $this.prev('.edit-registry-field');
                var fieldValLabel    = $this.prevAll('.value-label').first();

                $this
                    .prop('disabled', false)
                    .removeClass( 'loading' )
                    .next('.loading-spinner').remove().end();

                editRegistryLink.removeClass('show-input');

                if ( r.success == 1 ) {
                    $this.after('<span style="color:green;">'+ r.message +'</span>');
                    fieldValLabel.text(r.value);

                    if ( field === 'baptism_date' ) {

                        $('.input_benches[data-registry='+ registry +']').val('')
                            .find('option').each( function() { $(this).prop('disabled', false) } )
                            .prevAll('.value-label').text('').end();

                        if ( r.unavailable_benches ) {
                            r.unavailable_benches.forEach(function(b) {
                                $('.input_benches[data-registry='+ registry +'] option[value='+ b +']').prop('disabled', true);
                            });
                        }

                    } else if ( field === 'benches' && r.previous_bench ) {

                        const baptism_date = $('.input_baptism_date[data-registry='+ registry +']').val();

                        $('.input_baptism_date')
                            .filter( function(){ return this.value==baptism_date } )
                            .each( function() {
                                const _registry = $(this).attr('data-registry');

                                // Re-enable previous bench
                                $('.input_benches[data-registry='+ _registry +'] option[value='+ r.previous_bench +']').prop('disabled', false);

                                // Disable newly selected bench
                                $('.input_benches[data-registry='+ _registry +'] option[value='+ r.value +']').prop('disabled', true);
                            } );
                        
                    }
    
                    setTimeout( function() {
                        $this.next('span').fadeOut();
                    }, 700 );
                } else {
                    alert(r.message);
                }
            }
        )
    }
}); })(jQuery)