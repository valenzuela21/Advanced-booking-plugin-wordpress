<?php
class insertConsultProduct{

    public function __construct()
    {
        add_action( 'wp_ajax_nopriv_consult_import_booking', array($this,'consult_import_booking') );
        add_action( 'wp_ajax_consult_import_booking', array($this,'consult_import_booking') );
    }

    public function consult_import_booking(){

        if(isset( $_POST['respuesta'] ) ) {
            $respuesta = wp_unslash( $_POST['respuesta'] );
            array();
        }


        $id_general = $respuesta[1];
        $id_post = $respuesta[0];

        //Consult keys Firts Date Important
        $date_keys = get_post_meta( $id_post, '_date_key_'.$id_post,  true);
        $meta_firts = '_date_key_'.$id_general;
        $this->_insertCopyData($id_general, $meta_firts, $date_keys);

        foreach ( $date_keys as $date_key){

            $meta_key_1 = '_date_booking_'.$id_general.'_'.$date_key;

            $date_bookings= get_post_meta( $id_post, '_date_booking_'.$id_post.'_'.$date_key, true);

            $this->_insertCopyData($id_general, $meta_key_1,  $date_bookings);

            $meta_key_2 = '_date_booking_meta_'.$id_general .'_'.$date_key;

            $times_keys = get_post_meta( $id_post, '_date_booking_meta_'.$id_post.'_'.$date_key, true);

            $this->_insertCopyData($id_general, $meta_key_2, $times_keys);

            $this->timeBooking($id_general,  $id_post, $times_keys);

        }

    }

    public function timeBooking($id_general, $id_post, $times_keys){
        
        foreach ($times_keys as $id) {
            $meta_key_3 = '_time_booking_'.$id_general.'_'.$id;
            $times_bookings = get_post_meta($id_post , '_time_booking_'.$id_post.'_'.$id, true);
            $this->_insertCopyData($id_general, $meta_key_3, $times_bookings);
        }
        
   
     
    }


    public function _insertCopyData($id_general, $meta_key, $request){
        var_dump($id_general);
        update_post_meta( $id_general, $meta_key, $request);
    }

}
new insertConsultProduct();



