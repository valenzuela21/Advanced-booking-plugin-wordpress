(function ($) {

    $(document).on('click', '.single_add_to_cart_button', function (e) {
        e.preventDefault();
        var date_bookings = object.date_booking;
        var time_bookings = object.time_booking;
        
        var product_qty = $('input[name=quantity]').val() || 1;
        var product_id = $('input[name=product_id]').val();
        var variation_id = $('input[name=variation_id]').val() || 0;

        var date_time = $('#date-time').val();
        var date_booking = $('#date-booking').val();

        var f = new Date();
        var date_full = f.getFullYear() + "-" + ('0' + (f.getMonth() + 1)).slice(-2) + "-" + f.getDate();

        if (date_time === '' && date_booking === '') {

            $('.alert-modal-success').html(`<div class="content-alert-modal">
<div class="shaddow-alert-modal" ></div>
<div class="content-alert-description">
<em class="image-alert-calendar" > </em>
!You must first enter the date before booking¡
</div>
</div>`);

            setTimeout(function(){  $('.alert-modal-success').html(''); }, 2000);

        } else {
            
            var date_validate = date_bookings.includes(date_booking);
            var time_validate = time_bookings.includes(date_time);
            
            if (date_validate === true && time_validate === true) {

                $('.alert-modal-success').html(`<div class="content-alert-modal">
<div class="shaddow-alert-modal" ></div>
<div class="content-alert-description">
<em class="image-stop-calendar" > </em>
!This date and time has already been reserved¡
</div>
</div>`);
                setTimeout(function () {
                    $('.alert-modal-success').html('');
                }, 2000);
                
                return;
            }
            
              
              var m = new Date(date_booking);
              
              var date_start =  ('0' + (m.getMonth() + 1)).slice(-2);
              var date_end =  ('0' + (f.getMonth() + 1)).slice(-2);
            
            console.log("Date start: " + date_start + " Date End: " + date_end);
        
        
            if (date_start < date_end) {

                $('.alert-modal-success').html(`<div class="content-alert-modal">
<div class="shaddow-alert-modal" ></div>
<div class="content-alert-description">
<em class="image-stop-calendar" > </em>
!This date is no longer valid for the reservation¡
</div>
</div>`);
                setTimeout(function(){  $('.alert-modal-success').html(''); }, 2000);
return;
            } else {

                var data = {
                    action: 'woocommerce_ajax_add_to_cart',
                    product_id : product_id,
                    product_sku : '',
                    quantity : product_qty,
                    variation_id : variation_id,
                    date_time : date_time,
                    date_booking : date_booking

                };


                $.ajax({
                    type: 'post',
                    url: wc_add_to_cart_params.ajax_url,
                    data: data,
                    beforeSend: function (response) {
                        $('.alert-modal-success').html(`<div class="content-alert-modal">
<div class="shaddow-alert-modal" ></div>
<div class="content-alert-description">
<div class="spinner">
  <div class="bounce1"></div>
  <div class="bounce2"></div>
  <div class="bounce3"></div>
</div>
!Attaching to the cart¡
</div>
</div>`);
                    },
                    complete: function (response) {
                        $('.alert-modal-success').html(`<div class="content-alert-modal">
<div class="shaddow-alert-modal" ></div>
<div class="content-alert-description">
!Was inserted into the cart¡
</div>
</div>`);
                        location.reload();
                    },
                    success: function (response) {
                        if (response.error && response.product_url) {
                            window.location = response.product_url;
                            return;
                        } else {
                            location.reload();
                        }
                    },
                });

                return false;
            }
        }
    });

})(jQuery);