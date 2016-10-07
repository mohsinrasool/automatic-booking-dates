<?php
/**
 * Plugin Name: Automatic Booking Dates
 * Plugin URI: http://automatic-booking-dates.meticulousolutions.com
 * Description: Plugin allows to upload several (10 or more) lists of dates (in the format DD-MM-YY). Plugin then offers a series of shortcodes that can be inserted into posts and pages, in the format [booking_date list="booking-list"] and [event_date list="booking-list"].
 * Version: 1.0.0
 * Author: Mohsin Rasool
 * Author URI: http://meticulousolutions.com/
 * Requires at least: 4.6.0
 * Tested up to: 4.6.0
 *
 * Text Domain: automatic-booking-dates
 * Domain Path: /languages/
 *
 * @package Automatic_Booking_Dates
 * @category Core
 * @author Mohsin Rasool
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Returns the main instance of Automatic_Booking_Dates to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Automatic_Booking_Dates
 */
function Automatic_Booking_Dates() {
	return Automatic_Booking_Dates::instance();
} // End Automatic_Booking_Dates()

add_action( 'plugins_loaded', 'Automatic_Booking_Dates' );

/**
 * Main Automatic_Booking_Dates Class
 *
 * @class Automatic_Booking_Dates
 * @version	1.0.0
 * @since 1.0.0
 * @package	Automatic_Booking_Dates
 * @author Mohsin Rasool
 */
final class Automatic_Booking_Dates {
	/**
	 * Automatic_Booking_Dates The single instance of Automatic_Booking_Dates.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The token.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $token;

	/**
	 * The version number.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $version;

	/**
	 * The plugin directory URL.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $plugin_url;

	/**
	 * The plugin directory path.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $plugin_path;

	// Admin - Start
	/**
	 * The admin object.
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $admin;

	/**
	 * The settings object.
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings;
	// Admin - End

	// Post Types - Start
	/**
	 * The post types we're registering.
	 * @var     array
	 * @access  public
	 * @since   1.0.0
	 */
	public $post_types = array();
	// Post Types - End
	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 */
	public function __construct () {
		$this->token 			= 'automatic-booking-dates';
		$this->plugin_url 		= plugin_dir_url( __FILE__ );
		$this->plugin_path 		= plugin_dir_path( __FILE__ );
		$this->version 			= '1.0.0';

		// Admin - Start
		// require_once( 'classes/class-settings.php' );
		// 	$this->settings = Automatic_Booking_Dates_Settings::instance();

		// if ( is_admin() ) {
		// 	require_once( 'classes/class-admin.php' );
		// 	$this->admin = Automatic_Booking_Dates_Admin::instance();
		// }
		// Admin - End

		// Post Types - Start
		require_once( 'classes/class-post-type.php' );
		require_once( 'classes/class-shortcodes.php' );
		//require_once( 'classes/class-taxonomy.php' );

		// Register an example post type. To register other post types, duplicate this line.
		$this->post_types['date_list'] = new Automatic_Booking_Dates_Post_Type( 'date_list', __( 'Date List', $this->token ), __( 'Date Lists', $this->token ), array( 'menu_icon' => 'dashicons-carrot', 'supports'=> array('title') ) );
		// Post Types - End
		//

		Automatic_Booking_Dates_Shortcode::instance();

		register_activation_hook( __FILE__, array( $this, 'install' ) );

		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		add_action('admin_head', array( $this,'wpds_custom_admin_post_css') );
	} // End __construct()


	function wpds_custom_admin_post_css() {

	    global $post_type;

	    if ($post_type == 'date_list') {
	        echo "<style>.updated.notice.notice-success a {display:none;}</style>";
	    }
	}
	/**
	 * Main Automatic_Booking_Dates Instance
	 *
	 * Ensures only one instance of Automatic_Booking_Dates is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Automatic_Booking_Dates()
	 * @return Main Automatic_Booking_Dates instance
	 */
	public static function instance () {
		if ( is_null( self::$_instance ) )
			self::$_instance = new self();
		return self::$_instance;
	} // End instance()

	/**
	 * Load the localisation file.
	 * @access  public
	 * @since   1.0.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( $this->token, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	} // End load_plugin_textdomain()

	/**
	 * Cloning is forbidden.
	 * @access public
	 * @since 1.0.0
	 */
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
	} // End __clone()

	/**
	 * Unserializing instances of this class is forbidden.
	 * @access public
	 * @since 1.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
	} // End __wakeup()

	/**
	 * Installation. Runs on activation.
	 * @access  public
	 * @since   1.0.0
	 */
	public function install () {
		$this->_log_version_number();
	} // End install()

	/**
	 * Log the plugin version number.
	 * @access  private
	 * @since   1.0.0
	 */
	private function _log_version_number () {
		// Log the version number.
		update_option( $this->token . '-version', $this->version );
	} // End _log_version_number()
} // End Class
