<?php
include(dirname(__FILE__) . "/load.php");
$n = 6;

function getGenerateID($n)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';

    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }

    return $randomString;
}

$post_id = $_REQUEST['id_product'];
$key = $_REQUEST['key'];
$type = $_REQUEST['type'];

$meta = maybe_unserialize(get_post_meta($post_id, '_date_booking_meta_' . $post_id . '_' . $key));
$array = [];
$count = 1;
if (!empty($meta[0])) {
    foreach ($meta[0] as $key => $value) {
        $data_time = maybe_unserialize(get_post_meta($post_id, '_time_booking_' . $post_id . '_' . $value));
        if($type == 'copy_time'){
            $array_res[] = [
                "id" => $count,
                "min" => $data_time[0][0],
                "seg" => $data_time[0][1],
                "metakey" => getGenerateID($n),
                "symbol" => $data_time[0][2],
            ];
        }else{
            $array_res[] = [
                "id" => $count,
                "min" => $data_time[0][0],
                "seg" => $data_time[0][1],
                "metakey" => $value,
                "symbol" => $data_time[0][2],
            ];
        }

        $count++;
    }
} else {

    $array_res[] = [
        "id" => 1,
        "min" => '',
        "seg" => '',
        "metakey" => getGenerateID($n),
        "symbol" => '',
    ];

}

array_push($array, $array_res);
header("Content-type: application/json");
echo json_encode(array_reverse($array[0]));
die();