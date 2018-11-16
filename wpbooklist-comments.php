<?php
/**
 * WordPress Book List Comments Extension
 *
 * @package     WordPress Book List Comments Extension
 * @author      Jake Evans
 * @copyright   2018 Jake Evans
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: WPBookList Comments Extension
 * Plugin URI: https://www.jakerevans.com
 * Description: A WPBookList Extension that allows your visitors to leave Ratings and COMMENTS on your WPBookList Books!
 * Version: 6.0.0
 * Author: Jake Evans
 * Text Domain: wpbooklist
 * Author URI: https://www.jakerevans.com
 */

/*
 * SETUP NOTES:
 *
 * Change all filename instances from comments to desired plugin name
 *
 * Modify Plugin Name
 *
 * Modify Description
 *
 * Modify Version Number in Block comment and in Constant
 *
 * Find & Replace these 3 strings:
 * comments
 * Comments
 * COMMENTS
 *
 * Install Gulp & all Plugins listed in gulpfile.js
 *
 *
 *
 *
 *
 */




// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wpdb;

/* REQUIRE STATEMENTS */
	require_once 'includes/class-comments-general-functions.php';
	require_once 'includes/class-comments-ajax-functions.php';
/* END REQUIRE STATEMENTS */

/* CONSTANT DEFINITIONS */

	// Extension version number.
	define( 'COMMENTS_VERSION_NUM', '6.0.0' );

	// Root plugin folder directory.
	define( 'COMMENTS_ROOT_DIR', plugin_dir_path( __FILE__ ) );

	// Root WordPress Plugin Directory.
	define( 'COMMENTS_ROOT_WP_PLUGINS_DIR', str_replace( '/wpbooklist-comments', '', plugin_dir_path( __FILE__ ) ) );

	// Root WPBL Dir.
	define( 'ROOT_WPBL_DIR', COMMENTS_ROOT_WP_PLUGINS_DIR . 'wpbooklist/' );

	// Root WPBL Classes Dir.
	define( 'ROOT_WPBL_CLASSES_DIR', ROOT_WPBL_DIR . 'includes/classes/' );

	// Root WPBL Transients Dir.
	define( 'ROOT_WPBL_TRANSIENTS_DIR', ROOT_WPBL_CLASSES_DIR . 'transients/' );

	// Root WPBL Utilities Dir.
	define( 'ROOT_WPBL_UTILITIES_DIR', ROOT_WPBL_CLASSES_DIR . 'utilities/' );

	// Root plugin folder URL .
	define( 'COMMENTS_ROOT_URL', plugins_url() . '/wpbooklist-comments/' );

	// Root Classes Directory.
	define( 'COMMENTS_CLASS_DIR', COMMENTS_ROOT_DIR . 'includes/classes/' );

	// Root REST Classes Directory.
	define( 'COMMENTS_CLASS_REST_DIR', COMMENTS_ROOT_DIR . 'includes/classes/rest/' );

	// Root Compatability Classes Directory.
	define( 'COMMENTS_CLASS_COMPAT_DIR', COMMENTS_ROOT_DIR . 'includes/classes/compat/' );

	// Root Translations Directory.
	define( 'COMMENTS_CLASS_TRANSLATIONS_DIR', COMMENTS_ROOT_DIR . 'includes/classes/translations/' );

	// Root Transients Directory.
	define( 'COMMENTS_CLASS_TRANSIENTS_DIR', COMMENTS_ROOT_DIR . 'includes/classes/transients/' );

	// Root Image URL.
	define( 'COMMENTS_ROOT_IMG_URL', COMMENTS_ROOT_URL . 'assets/img/' );

	// Root Image Icons URL.
	define( 'COMMENTS_ROOT_IMG_ICONS_URL', COMMENTS_ROOT_URL . 'assets/img/icons/' );

	// Root CSS URL.
	define( 'COMMENTS_CSS_URL', COMMENTS_ROOT_URL . 'assets/css/' );

	// Root JS URL.
	define( 'COMMENTS_JS_URL', COMMENTS_ROOT_URL . 'assets/js/' );

	// Root UI directory.
	define( 'COMMENTS_ROOT_INCLUDES_UI', COMMENTS_ROOT_DIR . 'includes/ui/' );

	// Root UI Admin directory.
	define( 'COMMENTS_ROOT_INCLUDES_UI_ADMIN_DIR', COMMENTS_ROOT_DIR . 'includes/ui/admin/' );

	// Define the Uploads base directory.
	$uploads     = wp_upload_dir();
	$upload_path = $uploads['basedir'];
	define( 'COMMENTS_UPLOADS_BASE_DIR', $upload_path . '/' );

	// Define the Uploads base URL.
	$upload_url = $uploads['baseurl'];
	define( 'COMMENTS_UPLOADS_BASE_URL', $upload_url . '/' );

	// Nonces array.
	define( 'COMMENTS_NONCES_ARRAY',
		wp_json_encode(array(
			'adminnonce1' => 'wpbooklist_comments_like_action_callback',
			'adminnonce2' => 'wpbooklist_comments_submit_action_callback',
			'adminnonce3' => 'wpbooklist_comments_approve_action_callback',
			'adminnonce4' => 'wpbooklist_comments_edit_action_callback',
			'adminnonce5' => 'wpbooklist_comments_delete_action_callback',
			'adminnonce6' => 'wpbooklist_comments_maniparchived_action_callback',
			'adminnonce7' => 'wpbooklist_comments_submit_settings_action_callback',
			'adminnonce8' => 'wpbooklist_comments_login_action_callback',
			'adminnonce9' => 'wpbooklist_comments_register_action_callback',
		))
	);

/* END OF CONSTANT DEFINITIONS */

/* MISC. INCLUSIONS & DEFINITIONS */

	// Loading textdomain.
	load_plugin_textdomain( 'wpbooklist', false, COMMENTS_ROOT_DIR . 'languages' );

/* END MISC. INCLUSIONS & DEFINITIONS */

/* CLASS INSTANTIATIONS */

	// Call the class found in wpbooklist-functions.php.
	$comments_general_functions = new Comments_General_Functions();

	// Call the class found in wpbooklist-functions.php.
	$comments_ajax_functions = new Comments_Ajax_Functions();


/* END CLASS INSTANTIATIONS */


/* FUNCTIONS FOUND IN CLASS-WPBOOKLIST-GENERAL-FUNCTIONS.PHP THAT APPLY PLUGIN-WIDE */

	// Function that loads up the menu page entry for this Extension.
	add_filter( 'wpbooklist_add_sub_menu', array( $comments_general_functions, 'wpbooklist_comments_submenu' ) );

	// Adding the function that will take our COMMENTS_NONCES_ARRAY Constant from above and create actual nonces to be passed to Javascript functions.
	add_action( 'init', array( $comments_general_functions, 'wpbooklist_comments_create_nonces' ) );

	// Function to run any code that is needed to modify the plugin between different versions.
	add_action( 'plugins_loaded', array( $comments_general_functions, 'wpbooklist_comments_update_upgrade_function' ) );

	// Adding the admin js file.
	add_action( 'admin_enqueue_scripts', array( $comments_general_functions, 'wpbooklist_comments_admin_js' ) );

	// Adding the frontend js file.
	add_action( 'wp_enqueue_scripts', array( $comments_general_functions, 'wpbooklist_comments_frontend_js' ) );

	// Adding the admin css file for this extension.
	add_action( 'admin_enqueue_scripts', array( $comments_general_functions, 'wpbooklist_comments_admin_style' ) );

	// Adding the Front-End css file for this extension.
	add_action( 'wp_enqueue_scripts', array( $comments_general_functions, 'wpbooklist_comments_frontend_style' ) );

	// Function to add table names to the global $wpdb.
	add_action( 'admin_footer', array( $comments_general_functions, 'wpbooklist_comments_register_table_name' ) );

	// Function to run any code that is needed to modify the plugin between different versions.
	add_action( 'admin_footer', array( $comments_general_functions, 'wpbooklist_comments_admin_pointers_javascript' ) );

	// Creates tables upon activation.
	register_activation_hook( __FILE__, array( $comments_general_functions, 'wpbooklist_comments_create_tables' ) );

	// Runs once upon extension activation and adds it's version number to the 'extensionversions' column in the 'wpbooklist_jre_user_options' table of the core plugin.
	register_activation_hook( __FILE__, array( $comments_general_functions, 'wpbooklist_comments_record_extension_version' ) );

	// The function that outputs the actual comment and rating HTML.
	add_filter( 'wpbooklist_append_to_colorbox_comments', array( $comments_general_functions, 'wpbooklist_append_to_colorbox_comments_func' ) );



/* END OF FUNCTIONS FOUND IN CLASS-WPBOOKLIST-GENERAL-FUNCTIONS.PHP THAT APPLY PLUGIN-WIDE */

/* FUNCTIONS FOUND IN CLASS-WPBOOKLIST-AJAX-FUNCTIONS.PHP THAT APPLY PLUGIN-WIDE */

	// For receiving user feedback upon deactivation & deletion.
	add_action( 'wp_ajax_wpbooklist_comments_like_action', array( $comments_ajax_functions, 'wpbooklist_comments_like_action_callback' ) );
	add_action( 'wp_ajax_nopriv_wpbooklist_comments_like_action', array( $comments_ajax_functions, 'wpbooklist_comments_like_action_callback' ) );

	// Function that allows the user to log in from the Comments section.
	add_action( 'wp_ajax_wpbooklist_comments_login_action', array( $comments_ajax_functions, 'wpbooklist_comments_login_action_callback' ) );
	add_action( 'wp_ajax_nopriv_wpbooklist_comments_login_action', array( $comments_ajax_functions, 'wpbooklist_comments_login_action_callback' ) );

	// Function that allows the user to register from the Comments section.
	add_action( 'wp_ajax_nopriv_wpbooklist_comments_register_action', array( $comments_ajax_functions, 'wpbooklist_comments_register_action_callback' ) );

	// For submitting a new comment.
	add_action( 'wp_ajax_wpbooklist_comments_submit_action', array( $comments_ajax_functions, 'wpbooklist_comments_submit_action_callback' ) );
	add_action( 'wp_ajax_nopriv_wpbooklist_comments_submit_action', array( $comments_ajax_functions, 'wpbooklist_comments_submit_action_callback' ) );

	// For approving a comment from thte dashboard.
	add_action( 'wp_ajax_wpbooklist_comments_approve_action', array( $comments_ajax_functions, 'wpbooklist_comments_approve_action_callback' ) );

	// For approving a comment from the dashboard.
	add_action( 'wp_ajax_wpbooklist_comments_edit_action', array( $comments_ajax_functions, 'wpbooklist_comments_edit_action_callback' ) );

	// For deleting a comment from the dashboard.
	add_action( 'wp_ajax_wpbooklist_comments_delete_action', array( $comments_ajax_functions, 'wpbooklist_comments_delete_action_callback' ) );

	// For viewing or deleting archived coments from dashboard.
	add_action( 'wp_ajax_wpbooklist_comments_maniparchived_action', array( $comments_ajax_functions, 'wpbooklist_comments_maniparchived_action_callback' ) );

	// For viewing or deleting archived coments from dashboard.
	add_action( 'wp_ajax_wpbooklist_comments_submit_settings_action', array( $comments_ajax_functions, 'wpbooklist_comments_submit_settings_action_callback' ) );

	

/* END OF FUNCTIONS FOUND IN CLASS-WPBOOKLIST-AJAX-FUNCTIONS.PHP THAT APPLY PLUGIN-WIDE */






















