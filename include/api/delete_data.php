<?php
include(dirname(__FILE__) . "/load.php");
$data = json_decode(file_get_contents("php://input"), true);
$post_id = $data['idpost'];
$id_key = $data['idcolumn'];
$metakey = $data['metakey'];
$type = $data['type'];

switch ($type){
    case 'remove_time':
        $meta_key = '_date_booking_meta_'.$post_id.'_'.$metakey;
        $time_booking = '_time_booking_'.$post_id.'_'.$id_key;

        $meta = maybe_unserialize(get_post_meta($post_id, $meta_key));
        $meta = $meta[0];

        if (in_array($id_key,$meta)){
            $key_meta = array_search($id_key,$meta);
            unset($meta[$key_meta]);
        }

        update_post_meta( $post_id, $meta_key, $meta);
        delete_post_meta( $post_id, $time_booking, '' );
        break;
    default:
        $meta_key = '_date_booking_meta_'.$post_id.'_'.$metakey;
        $meta_booking = '_date_booking_'.$post_id.'_'.$metakey;
        $meta_date_key= '_date_key_'.$post_id;

        $meta = maybe_unserialize(get_post_meta($post_id, $meta_date_key));
        $meta = $meta[0];

        if (in_array($metakey,$meta)){
            $key_meta = array_search($metakey,$meta);
            unset($meta[$key_meta]);
        }

        update_post_meta( $post_id, $meta_date_key, $meta);
        delete_post_meta( $post_id,  $meta_booking, '' );
        delete_post_meta( $post_id,  $meta_key, '' );
        break;
}