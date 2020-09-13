<?php
include(dirname(__FILE__)."/load.php");
$post_id = $_REQUEST['id_product'];

$n=6;

function getGenerateID($n) { 
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
    $randomString = ''; 
  
    for ($i = 0; $i < $n; $i++) { 
        $index = rand(0, strlen($characters) - 1); 
        $randomString .= $characters[$index]; 
    } 
  
    return $randomString; 
} 

$meta = get_post_meta($post_id, '_date_key_'.$post_id);
$array = [];
if(!empty($meta[0])){
    $count = 1;
    foreach ($meta[0] as $key=>$value){
        $date=get_post_meta($post_id, '_date_booking_'.$post_id.'_'.$value);
    
        $array_res[]=[
            "id"=>$count++,
            "metakey" =>$meta[0][$key],
            "date"=> $date[0]
        ];
    }
     
}else{
    
     $array_res[]=[
            "id" => 1,
            "metakey" => getGenerateID($n),
            "date" => ''
        ];

}
    array_push($array, $array_res);
    
    
    header("Content-type: application/json");
    echo json_encode(array_reverse($array[0])) ;
    die();