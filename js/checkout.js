'use strict';

function main($)
{
    const container = $('.woocommerce-innocard-checkout-form');
    if ( !container.get(0) ) return;

    const checkbox = $('.wc-innocard-checkbox-use-card');
    const payment_fields = container.find('.wc-innocard-payment-fields');
    
    const card_number_item = payment_fields.find('.wc-innocard-field-card-number');
    const card_number_field = card_number_item.find('input');
    
    const pin_item = payment_fields.find('.wc-innocard-field-pin');
    const pin_field = pin_item.find('input');
    
    const balance_item = container.find('.wc-innocard-field-balance');
    const balance_field = balance_item.find('input');
    
    const check_balance_button = container.find('.wc-innocard-button-check-balance');

    const success_alert = container.find('.success');
    const error_alert = container.find('.error');

    const confirm_discount = container.find('.confirm-discount');
    const remove_discount = container.find('.remove-discount');

    const _wpnonce = $('#wc-innocard-nonce').val();

    /**
     * If init with value, discount has been applied earlier
     */
    // if (balance_field.val()) {
    //     balance_field.attr('disabled', 'disabled');
    // }

    /**
     * Helpers
     */
    let card_info = {};
    let get_data = () => {
        
        let rand = Math.abs( Math.random() * 10000 );

        return {
            ...card_info,
            card_number: card_number_field.val() && card_number_field.val().replace(/\D/g, ''),
            pin: pin_field.val() && pin_field.val().replace(/\D/g, ''),
            invalid: false,
            balance: balance_field && balance_field.val(),
            rand,
            _wpnonce
        };
    }

    let validate_data = () => {
        let data = get_data();

        if (!data.card_number || data.card_number.length < 10 ) {
            card_number_item.addClass('woocommerce-invalid');
            data.invalid = true;
        }

        if (!data.pin || data.pin.length != 6 ) {
            pin_item.addClass('woocommerce-invalid');
            data.invalid = true;
        }

        if (data.invalid) {
            return false;
        }

        return true;
    }

    /**
     * Show input
     */
    checkbox.on('change', function(ev) {
        if ( checkbox.prop('checked') ) {
            payment_fields.removeClass('hidden')
            return;
        } 
        payment_fields.addClass('hidden');
        remove_discount.trigger('click');
    }).change();
        
    check_balance_button.on('click', async (ev) => {
        ev.preventDefault();

        error_alert.addClass('hidden');
        success_alert.addClass('hidden');

        balance_field.removeAttr('min')
        balance_field.removeAttr('max').val('');

        let data = get_data();
        let valid = validate_data();

        if (!valid) return;
 
        $(container).block({ message: null });

        let cart_total = 0;
        /** Get cart total while card balance loading */
        cart_total = await new Promise((resolve) => {
            $.get( container.find('#wc-wordpress-api-endpoint').val() + 'wc/store/cart' )
            .then(success => {
                cart_total = parseFloat((parseInt( success.totals.total_price ) / 100 ));
                resolve(cart_total)
            })
        });

        $.get( check_balance_button.attr('data-action'), data, { hxrFields: { withCredentials: true } } )
        .success( async (ev) => {
            
            card_info['customer'] = ev.customer;
            card_info['current_balance'] = ev.balance;

            if ( cart_total == 0 ) {
                await new Promise((resolve) => setTimeout(() => resolve(true) ), 500 );
            }

            let total = cart_total > ev.balance ? ev.balance : cart_total;
            balance_field.val(
                parseFloat(total).toFixed(2)
            );

            success_alert.html(ev.message).removeClass('hidden');
            
            balance_field.attr('max', parseFloat(total).toFixed(2));
            balance_item.removeClass('hidden');

            confirm_discount.removeClass('hidden');
            //balance_field.removeAttr('disabled');
        })
        .error(e => {
            error_alert.html( ( e.responseJSON && e.responseJSON.message ) || e.responseText ).removeClass('hidden');
        })
        .always( () => {
            $(container).unblock();
        })
    })

    confirm_discount.on('click', (e) => {
        let data = get_data();
        let valid = validate_data();

        if (!valid) return;

        if ( !data.balance ) {
            balance_item.addClass('woocommerce-invalid');
            return;
        }

        error_alert.addClass('hidden');
        success_alert.addClass('hidden');

        container.block({ message: null });
        $.post( confirm_discount.attr('data-action') + '?random=' + data.rand, data, { xhrFields: { withCredentials: true } } )
        .success( ev => {
            
            success_alert.html(ev.message).removeClass('hidden');
            check_balance_button.addClass('hidden');
            remove_discount.removeClass('hidden');
            //confirm_discount.addClass('hidden');
            //balance_field.attr('disabled', 'disabled');
            
            card_number_field.attr('disabled', true);
            pin_field.attr('disabled', true);
            
            $('body').trigger('update_checkout');
        })
        .error( e => {
            console.log(e);
            error_alert
            .html( ( e.responseJSON && e.responseJSON.message ) || e.responseText )
            .removeClass('hidden');
        })
        .always( () => {
            container.unblock()
        })
    });

    remove_discount.on('click', (ev) => {
        ev.preventDefault();

        error_alert.addClass('hidden');
        success_alert.addClass('hidden');

        container.block({ message: '' });

        $.get( remove_discount.attr('href'))
        .success(() => {
            balance_item.addClass('hidden');
            balance_field.val('');
            
            card_number_field.val('').removeAttr('disabled');
            pin_field.val('').removeAttr('disabled');

            confirm_discount.addClass('hidden');
            remove_discount.addClass('hidden');
            
            payment_fields.addClass('hidden');
            checkbox.prop('checked', false);

            check_balance_button.removeClass('hidden');

            $('body').trigger('update_checkout');

        })
        .always(() => {
            container.unblock();
        })
    })
}

window.addEventListener('DOMContentLoaded', function() {
    main(jQuery);
})