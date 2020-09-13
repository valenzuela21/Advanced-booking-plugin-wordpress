<?php
/*
Plugin Name:  Booking Service Time and Date
Plugin URI:   https://creatives.com.co/
Description:  Component in charge of quoting or booking online appointments
Version:      1.0
Author:       David Fernando Valenzuela Pardo
Author URI:   https://creatives.com.co/
License:      GPL2
License URI:  GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
Text Domain:  time-date-booking
*/


if( ! defined( 'ABSPATH' ) ) {
    exit;
}

define('PATH_FILE', plugin_dir_path(__FILE__));


if( ! class_exists( 'TPWCP_Admin' ) ) {
    class TPWCP_Admin {

        public function __construct() {
            // Create the custom tab
            add_filter( 'woocommerce_product_data_tabs', array( $this, 'create_giftwrap_tab' ) );
            // Add the custom fields
            add_action( 'woocommerce_product_data_panels', array( $this, 'display_giftwrap_fields' ) );
            //Add Require Field
            add_action('init', array($this,'require_file_metabox'));
            //Add_icon
            add_action('admin_head', array($this,'misha_css_icon'));

        }

        /**
         * Add the new tab to the $tabs array
         * @see     https://github.com/woocommerce/woocommerce/blob/e1a82a412773c932e76b855a97bd5ce9dedf9c44/includes/admin/meta-boxes/class-wc-meta-box-product-data.php
         * @param   $tabs
         * @since   1.0.0
         */
        public function create_giftwrap_tab( $tabs ) {
            $tabs['giftwrap'] = array(
                'label'         => __( 'Appoiment', 'time-date-booking' ), // The name of your panel
                'target'        => 'gifwrap_panel', // Will be used to create an anchor link so needs to be unique
                'class'         => array( 'giftwrap_tab', 'show_if_simple', 'show_if_variable' ), // Class for your panel tab - helps hide/show depending on product type
                'priority'      => 80, // Where your panel will appear. By default, 70 is last item
            );
            return $tabs;
        }

        public function require_file_metabox(){

            require_once PATH_FILE . './include/view_metabox_config.php';
            require_once PATH_FILE . './include/frond_product_hook.php';
            require_once PATH_FILE . './include/api/add_cart_ajax.php';
            require_once PATH_FILE . './include/api/add_copy_new_data.php';

        }
        /**
         * Display fields for the new panel
         * @see https://docs.woocommerce.com/wc-apidocs/source-function-woocommerce_wp_checkbox.html
         * @since   1.0.0
         */
        public function display_giftwrap_fields() {
            $view_config_tab = new metaboxViewConfig();
            ?>

<div id='gifwrap_panel' class='panel woocommerce_options_panel'>
    <div class="options_group">
        <?php 
                if(empty($_GET['post'])){
                        echo'<div class="text-alert-center">';
                        echo __('!You have to publish your content first¡','time-date-booking');
                        echo'</div>';
                }else{
                        $view_config_tab->view_tab_config();
                    }
                ?>

    </div>
</div>

<?php }

        function misha_css_icon(){
            echo '<style>
	#woocommerce-product-data ul.wc-tabs li.giftwrap_options.giftwrap_tab a:before{
		content: "\f487";
	}
	</style>';
        }


    }
    new TPWCP_Admin();
}