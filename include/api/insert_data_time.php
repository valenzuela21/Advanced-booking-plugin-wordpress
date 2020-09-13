<?php
include(dirname(__FILE__) . "/load.php");
$data = json_decode(file_get_contents("php://input"), true);
$type = $data['type'];
switch ($type) {
    case '_date_booking_meta_':
      $id_post = $data['id_post'];
      $data_key = $data['metakey'];
      $id_key = '_date_key_'.$id_post;
      $metakey = '_date_booking_'.$id_post.'_'.$data_key;
      $idcolumn = $data['type'].$id_post.'_'.$data_key;
      $date_new= $data['time_seg_type'];

      $id = maybe_unserialize(get_post_meta($id_post, $id_key));
      $array_finaly = array_merge((array)$data_key, $id[0]);
      $array_finaly = array_unique($array_finaly);

      if (!empty($id[0])) {
            $list = [];

            foreach ($array_finaly as $value) {
                array_push($list, $value);
            }

        } else {
            $list = (array)$data_key;
        }

        //Validate Meta.
        $meta = maybe_unserialize(get_post_meta($id_post, $idcolumn));

        if (!empty($meta[0])){
            $meta = $meta[0];
        }else{
            $meta = '';
        }
        

      update_post_meta($id_post, $metakey, wp_slash($date_new));
      update_post_meta($id_post, $id_key, wp_slash($list));
      update_post_meta($id_post, $idcolumn, $meta);
      
    break;
    case '_time_booking_meta_':
            $id_post = $data['id_post'];
            $data_key = $data['metakey'];
            $key = $data['key'];
            $time_new = $data['time_seg_type'];

            $id_key = '_date_booking_meta_'.$id_post.'_'.$key;
            $metakey = '_time_booking_'.$id_post .'_'.$data_key;
          

            $id = maybe_unserialize(get_post_meta($id_post, $id_key));
            $array_finaly = array_merge((array)$data_key, $id[0]);
            $array_finaly = array_unique($array_finaly);

             if (!empty($id[0])) {
                 $list = [];

                foreach ($array_finaly as $value) {
                    array_push($list, $value);
                }

              } else {
                $list = (array)$data_key;
            }


            update_post_meta($id_post, $metakey, wp_slash($time_new));
            update_post_meta($id_post, $id_key, wp_slash($list));
    default:
    
    break;

}