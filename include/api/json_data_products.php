<?php
include(dirname(__FILE__)."/load.php");
$args= array(
    'post_type' => 'product',
    'posts_per_page'  => -1,
    "orderby" => "date",
    "order" => "DESC"
);

$the_query = new WP_Query( $args );
$response = [];
if ( $the_query->have_posts() ) {
    while ( $the_query->have_posts() ) {
        $the_query->the_post();
        $id = get_the_ID();
        $response[] = array("id"=>"$id", "text"=>get_the_title());
    }
}
echo json_encode($response);
/* Restore original Post Data */
wp_reset_postdata();