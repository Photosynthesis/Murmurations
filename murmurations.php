<?php

/**
 * Plugin Name:       Murmurations
 * Plugin URI:        murmurationsprotocol.org
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Murmurations
 * Author URI:        uri
 * License:           Peer Production License
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       murmurations
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

LazyLog::setSetting('bufferLog',true);

define('MURMURATIONS_VERSION', '1.0.0');
define('MURM_DATA_OPT_KEY', 'murmurations_data');
define('MURM_SCHEMA_OPT_KEY', 'murmurations_schema');
define('MURM_SETTINGS_OPT_KEY', 'murmurations_settings');


function activate_murmurations() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-murmurations-activator.php';
	Murmurations_Activator::activate();
}

function deactivate_murmurations() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-murmurations-deactivator.php';
	Murmurations_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_murmurations' );
register_deactivation_hook( __FILE__, 'deactivate_murmurations' );

/* Include the wordpress class */
require plugin_dir_path( __FILE__ ) . 'includes/class-murmurations-wp.php';

add_action('wp_footer', 'murms_flush_log');
add_action('admin_footer', 'murms_flush_log');

function murms_flush_log(){
   echo "<div style=\"margin-left:200px;\">";
   LazyLog::flush();
   echo "</div>";
}

// Ajax action to refresh the logo image
add_action( 'wp_ajax_murmurations_get_image', 'murmurations_get_image'   );

function murmurations_get_image() {
    if(isset($_GET['id']) ){
        $image = wp_get_attachment_image( filter_input( INPUT_GET, 'id', FILTER_VALIDATE_INT ), 'medium', false, array( 'id' => 'murmurations-preview-image' ) );
        $data = array(
            'image'    => $image,
        );
        wp_send_json_success( $data );
    } else {
        wp_send_json_error();
    }
}

function run_murmurations_wp() {

	$plugin = new Murmurations_WP();
	$plugin->run();

}
run_murmurations_wp();
?>
