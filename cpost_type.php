<?php
/**
 * Plugin Name: Authors Post
 * Plugin URI: https://github.com/alamintpi/plugin-development
 * Github Plugin URI: https://github.com/alamintpi/plugin-development
 * Description: Themefic Test Plugin
 * Author: Themefic
 * Author URI: https://themefic.com
 * Version: 1.0.0
 * Text Domain: cposttype
 * Domain Path: /lang
 *
 */

/**
* Including Plugin file for security
* Include_once
*
* @since 1.0.0
*/
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );


define( 'Ccpt_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'Ccpt_PLUGIN_VERSION', '1.0' );

/**
 *  Plugin Main Class
 */

final class Ccpt_post_type {

    private function __construct() {
        // Loaded textdomain
        add_action('plugins_loaded', array( $this, 'plugin_loaded_action' ), 10, 2);

        // Enqueue frontend scripts
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ), 100 );

        // Added plugin action link
        add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );

        // trigger upon plugin activation/deactivation
        register_activation_hook( __FILE__, array( $this, 'plugin_activation' ) );

    }

    /**
     * Initialization
     */
    public static function init(){
        static $instance = false;

        if ( ! $instance ) {
            $instance = new self();
        }
        return $instance;
    }

    /**
     * Adds plugin action links.
     */
    function action_links( $links ) {
         
        return array_merge( $links );
    }

    /**
     * Plugin Loaded Action
     */
    function plugin_loaded_action() {
        // Loading Text Domain for Internationalization
        load_plugin_textdomain( 'Ccpt', false, dirname( plugin_basename(__FILE__) ) . '/lang/' );

        require_once( dirname( __FILE__ ) . '/admin/functions.php' );
        require_once( dirname( __FILE__ ) . '/admin/SearchHandler.php' );
    }

    /**
     * Enqueue Frontend Scripts
     */
    function enqueue_scripts() {
        $ver = current_time( 'timestamp' );

        wp_enqueue_style( 'Ccpt-styles', Ccpt_PLUGIN_URL . 'assets/css/styles.css', null, $ver );
        wp_enqueue_script( 'Ccpt-scripts', Ccpt_PLUGIN_URL . 'assets/js/scripts.js', array('jquery'), $ver );
        wp_localize_script( 'Ccpt-scripts', 'my_ajax_object',
            array(
                'nonce' => wp_create_nonce( 'Ccpt_nonce' ),
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
            )
         );

    }

    /**
    *  Plugin Activation
    */
    function plugin_activation() {

        if ( ! get_option( 'Ccpt_installed' ) ) {
            update_option( 'Ccpt_installed', time() );
        }

        update_option( 'Ccpt_plugin_version', Ccpt_PLUGIN_VERSION );

    }

    /**
    *  Plugin Deactivation
    */
    function plugin_deactivation() {

    }

    /**
     * Enqueue admin script
     *
     */
    function admin_scripts( $hook ) {
        if ( 'options-permalink.php' != $hook ) {
            //return;
        }
        $ver = current_time( 'timestamp' );
        
        wp_enqueue_media();

        wp_enqueue_style( 'Ccpt-admin-styles', Ccpt_PLUGIN_URL . 'admin/assets/css/admin.css', null, $ver );
        wp_enqueue_script( 'Ccpt-admin-scripts', Ccpt_PLUGIN_URL . 'admin/assets/js/admin.js', array('jquery'), $ver );


    }

}


/**
 * Initialize plugin
 */
function ccpt_post_type(){
    return Ccpt_post_type::init();
}

// Let's start it
ccpt_post_type();

