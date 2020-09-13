<?php

if (!defined('ABSPATH')) {
    exit;
}

class viewFrondBooking
{

    function __construct()
    {
        add_action('woocommerce_single_product_summary', array($this, 'my_function_custom_archive_description'), 20, 5);
        add_action('wp_enqueue_scripts', array($this, 'style_frond_booking'));
        add_filter('woocommerce_get_item_data', array($this, 'iconic_display_engraving_text_cart'), 10, 2);
        add_filter( 'woocommerce_order_item_name', array($this,'kia_woocommerce_order_item_name'), 10, 2 );
	    add_action( 'woocommerce_before_order_itemmeta', array($this,'woocommerce_before_order_itemmeta'), 10, 3 );
		
    }

    public function style_frond_booking()
    {
        if (function_exists('is_product') && is_product()) {
            
            $date_day = add_cartBooking::_consult_date();
            $date_time = add_cartBooking::_consult_time();
            
            
            wp_enqueue_style('booking_forntend_dzscalendar', plugins_url('./../css/dzscalendar.css', __FILE__));
            wp_enqueue_style('booking_forntend_stooltip', plugins_url('./../css/dzstooltip.css', __FILE__));

            wp_enqueue_script('booking_script_handler_counter', plugins_url('./../js/handleCounter.js', __FILE__), array('jquery'), 1.0, true);
            wp_enqueue_script('booking_script_dzscalendar_js', plugins_url('./../js/script_calendar.js', __FILE__), array('jquery'), 1.0, true);
            wp_enqueue_script('booking_script_dzscalendar', plugins_url('./../js/dzscalendar.js', __FILE__), array('jquery'), 1.0, true);
            wp_enqueue_script('woocommerce-add-to-cart', plugin_dir_url(__FILE__) . './../js/ajax-add-to-cart.js', array('jquery'), '', true);

            // Localize the script with new data
            $translation_array = array(
                'id' => get_the_ID(),
                'month' => array(__('January', 'time-date-booking'), __('February', 'time-date-booking'), __('March', 'time-date-booking'), __('April', 'time-date-booking'), __('May', 'time-date-booking'), __('June', 'time-date-booking'), __('July', 'time-date-booking'), __('August', 'time-date-booking'), __('September', 'time-date-booking'), __('October', 'time-date-booking'), __('November', 'time-date-booking'), __('December', 'time-date-booking')),
                'month_calendar' => array(__('SUNDAY', 'time-date-booking'), __('MONDAY', 'time-date-booking'), __('TUESDAY', 'time-date-booking'), __('WEDNESDAY', 'time-date-booking'), __('THURSDAY', 'time-date-booking'), __('FRIDAY', 'time-date-booking'), __('SATURDAY', 'time-date-booking'))
            );

            wp_localize_script('booking_script_dzscalendar', 'object_calendar', $translation_array);
            
            
             wp_localize_script( 'woocommerce-add-to-cart', 'object',
                array(
                    'date_booking' => $date_day,
                    'time_booking' => $date_time,
                )
            );
        }

    }

    public function my_function_custom_archive_description()
    {
        global $product;
        
        $date_day = add_cartBooking::_consult_date();
        $date_time = add_cartBooking::_consult_time();
        
        $precing_product = $product->get_price();
        $post_id = get_the_ID();
        $meta_keys = maybe_unserialize(get_post_meta($post_id, '_date_key_' . $post_id));
        if (count($meta_keys[0]) <= 0) {
            echo "<div>" . __('¡No dates or times created!', 'time-date-booking') . "</div>";
        } else {
	$calendar_booking = get_post_meta($post_id,'_config_booking_calendar');
    if($calendar_booking[0] != 'on'){
            echo '
            <div><p style="color:#77844f">'.__('Note: The highlighted squares are the days available.','time-date-booking').'</p></div>
            <section>
<div class="one-half column omega">
    <div class="dzscalendar skin-aurora" id="traurora" style="margin:20px auto;">
        <div class="events">';
            foreach ($meta_keys[0] as $key => $value) {
                $booking_date = get_post_meta($post_id, '_date_booking_' . $post_id . '_' . $value);
                
                $date_general = $booking_date[0];
                
                $date=date_create( $date_general);
                $date = date_format($date,"d-m-Y");
 
                $booking_date = explode('-', $booking_date[0]);

                echo '<div class="event-tobe" data-day="' . $booking_date[2] . '" data-month="' . $booking_date[1] . '" data-year="' . $booking_date[0] . '">
                <div style="width:100%;">
                    <h4 class="title-date"><i class="icons-calendar"></i><span class="parrafo-aditional">' . __('Date: ', 'time-date-booking') . $date . '</span></h4>
                    <h4 class="titel-booking"><i class="icons-time"></i><span class="parrafo-aditional">' . __('Available times: ', 'time-date-booking') . '</span></h4>
                    <div class="section-time">';


                $booking_meta_time = get_post_meta($post_id, '_date_booking_meta_' . $post_id . '_' . $value);

                if (is_array($booking_meta_time)) {
                    $booking_meta_time = $booking_meta_time[0];
                } else {
                    $booking_meta_time = $booking_meta_time;
                }

                if (empty($booking_meta_time[0])) {
                    echo '<div class="text-alert-booking-calendar">' . __('¡There are no times created!', 'time-date-booking') . '</div>';
                } else {

                    foreach ($booking_meta_time as $value) {
                        $time_booking = get_post_meta($post_id, '_time_booking_' . $post_id . '_' . $value);
                        
                        $time = $time_booking[0][0] . ':' . $time_booking[0][1] . ' ' . $time_booking[0][2];
                        
                        if(!in_array($time, $date_time) || !in_array($date_general, $date_day)){
                              echo '
                        <button class="time-select" onclick="myBooking(event)"  time="' . $time . '" date="' . $date_general . '"  >' . $time . '</button>';
                        }
                      
                    }
                }
                echo '</div>
                 </div>
                 </div>
             ';
            }
            echo '</div>
    </div>
</div>
<div class="form-booking">
<div id="text-info-booking"></div>
<label class="label-booking">' . __('Number Persons:', 'time-date-booking') . '</label>
 <div class="handle-counter" id="handleCounter">
            <button class="counter-minus btn_counter">-</button>
            <input type="text" name="quantity" class="booking-counter" value="1">
            <button class="counter-plus btn_counter">+</button>
        </div>
        <div style="margin-top: 20px">
        <input type="text" style="display:none" value="" id="date-time" />
        <input type="text" style="display:none" value="" id="date-booking" />
        <input type="text" style="display:none;" value="' . $post_id . '" name="product_id" />
        <button class="single_add_to_cart_button" type="submit" value="' . $post_id . '" >' . __('Booking', 'time-date-booking') . '</button>
        </div>
</div>
</div>
</section>';
	}		
echo '<div class="alert-modal-success"></div>';

        }

    }


    public function iconic_display_engraving_text_cart($item_data, $cart_item)
    {

        if (isset($cart_item["variation"] ["custom_data"])) {
            $customdata = $cart_item["variation"] ["custom_data"];
            
            $date = $customdata['datebooking'];
            $create_date = date_create($date);
            $dateBooking = date_format($create_date,"d-m-Y"); 
            
            echo '<ul>
                        <li>' . __('Time: ', 'time-date-booking') . $customdata['datetime'] . '</li>
                         <li>' . __('Date: ', 'time-date-booking') . $dateBooking . '</li>
                        </ul>';
        }

    }

    public function kia_woocommerce_order_item_name( $name, $item ){
        $meta_data = $item->get_meta_data();
        $meta_data = $meta_data[0]->get_data();
        $meta_data = $meta_data['value'];
        $date = $meta_data["datebooking"];
        $time = $meta_data["datetime"];
    
        $create_date = date_create($date);
        $dateBooking = date_format( $create_date,"d-m-Y");

        if(!empty($date) && !empty($time)) {
            $content  ="<div style='display:flex'>";
            $content .="<div style='width: 110px'>";
            $content .= $name . " ";
            $content .="</div>";
            $content .="<div style='width: 145px; font-size: 12px;'>";
            $content .="<ul>";
            $content .="<li><strong>" . __("Time:", "time-date-booking") . "</strong> " . $time . " </li>";
            $content .="<li><strong>" . __("Date:", "time-date-booking") . "</strong> " . $dateBooking . "</li></ul>";
            $content .="</div></div>";
            return  $content;
        }
    }

    public function woocommerce_before_order_itemmeta( $item_id, $item, $_product ){
        $meta_data = $item->get_meta_data();
        $meta_data = $meta_data[0]->get_data();
        $meta_data = $meta_data['value'];
        $date = $meta_data["datebooking"];
        $time = $meta_data["datetime"];
        
        
        $create_date = date_create($date);
        $dateBooking = date_format($create_date,"d-m-Y"); 
        
        if(!empty($date) && !empty($time)) {
            echo "<p><strong>" . __("Time:", "time-date-booking") . "</strong> " . $time . " </p>";
            echo "<p><strong>" . __("Date:", "time-date-booking") . "</strong> " . $dateBooking . "</p>";
        }
    }


}

new viewFrondBooking();




