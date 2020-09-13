<?php


class add_cartBooking{

    function __construct()
    {
        add_action('wp_ajax_woocommerce_ajax_add_to_cart', array($this,'woocommerce_ajax_add_to_cart'));
        add_action('wp_ajax_nopriv_woocommerce_ajax_add_to_cart', array($this,'woocommerce_ajax_add_to_cart'));
        add_action( 'woocommerce_order_status_processing', array($this,'natur_woocommerce_order_status_processing'), 10, 1 );
    }

    public function woocommerce_ajax_add_to_cart() {

        $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
        $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
        $variation_id = absint($_POST['variation_id']);
        $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
        $product_status = get_post_status($product_id);
        $date_time = sanitize_text_field($_POST['date_time']);
        $date_booking = sanitize_text_field($_POST['date_booking']);
        $custom_data = [];

        if(isset($date_time) && !empty($date_time)){
            $custom_data['custom_data']['datetime'] =  $date_time;
        }

        if(isset($date_booking) && !empty($date_booking)){
            $custom_data['custom_data']['datebooking'] = $date_booking;
        }

        if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $custom_data) && 'publish' === $product_status) {

            do_action('woocommerce_ajax_added_to_cart', $product_id);

            if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {
                wc_add_to_cart_message(array($product_id => $quantity), true);
            }

            WC_AJAX :: get_refreshed_fragments();
        } else {

            $data = array(
                'error' => true,
                'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id));

            echo wp_send_json($data);
        }

        wp_die();
    }
    
     public function natur_woocommerce_order_status_processing( $order_id ) {

        $order = wc_get_order( $order_id );

        foreach ($order->get_items() as $key => $value){
            $date_new = $value['custom_data']["datebooking"];
            $date_day = $value['custom_data']["datetime"];
        }

        update_post_meta( $order_id, '_date_booking_reserve', wp_slash($date_new) );
        update_post_meta( $order_id, '_time_booking_reserve', wp_slash($date_day) );
    }
    
     public static function _consult_date(){

        global $wpdb;

        $dates_bookings = [];

        $table = $wpdb->prefix . "postmeta";

        $res = $wpdb->get_results( "SELECT * FROM $table WHERE meta_key = '_date_booking_reserve'" );

        foreach ($res as $item){

           $date_booking =$item->meta_value;

           array_push($dates_bookings, $date_booking);

        }

        return $dates_bookings;
    }

    public static function _consult_time(){
        global $wpdb;

        $times_bookings = [];

        $table = $wpdb->prefix . "postmeta";

        $res = $wpdb->get_results( "SELECT * FROM $table WHERE meta_key = '_time_booking_reserve'" );

        foreach ($res as $item){

            $time_booking = $item->meta_value;

            array_push($times_bookings, $time_booking);

        }

        return $times_bookings;
    }


}
new add_cartBooking();


