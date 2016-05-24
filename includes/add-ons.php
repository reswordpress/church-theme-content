<?php/** * Add-ons (Global) * * Global functions pertaining to add-ons. * * @package    Church_Theme_Content * @copyright  Copyright (c) 2014, churchthemes.com * @link       https://github.com/churchthemes/church-theme-content * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html * @since      1.2 */// No direct accessif ( ! defined( 'ABSPATH' ) ) exit;/************************************************* * REGISTRATION *************************************************//** * Register Add-on * * An add-on can register itself using this function. * Add-on data is added to a global array with other add-ons. * This array is used to handle License Key settings, one-click updates, expiration notices, etc. * * You can register your add-on with minimal arguments like this: * *	function prefix_register_add_on() { * *		if ( function_exists( 'ctc_register_add_on' ) ) { * *			ctc_register_add_on( array( *				'plugin_file'		=> __FILE__,													// Full path to plugin main file (__FILE__ if this code is in main file) *				'store_url'			=> 'https://churchthemes.com',									// URL of store running EDD with Software Licensing extension *				'renewal_url'		=> 'https://churchthemes.com/renew/?license_key={license_key}',	// It is recommended to provide a URL for renewal links (ie. redirecting to EDD checkout); {license_key} will be replaced with key *				'renewal_info_url'	=> 'https://churchthemes.com/go/license-renewal', 				// Optional URL for renewal information *			) ); * *		} * *	} * * 	add_action( 'plugins_loaded', 'prefix_register_add_on' ); * * See ctc_register_add_on() for all possible arguments (most are automatically filled but can be overridden). * It is highly recommended that a renewal_url is provided to make license renewal easy for users. * * @since 1.2 * @param array $args Add-on data including what is necessary for EDD Software Licensing * @global array $ctc_add_ons */function ctc_register_add_on( $args ) {	global $ctc_add_ons;	// Prepare array to receive add-ons	if ( ! isset( $ctc_add_ons ) ) {		$ctc_add_ons = array();	}	// Add add-on to global	if ( ! empty( $args['plugin_file'] ) ) {		// Plugin file relative path		// plugin-name/plugin-name.php		$args['plugin_file_base'] = plugin_basename( $args['plugin_file'] );		// Plugin's directory name (e.g. plugin-name)		// This serves as a nice clean, unique slug -- good for use in settings		$args['plugin_dir'] = dirname( $args['plugin_file_base'] ) ;		// Get plugin data		// wp-admin/includes/plugin.php is already included by Church_Theme_Content class		$plugin_data = current( get_plugins( '/' . $args['plugin_dir'] ) );		// Plugin name		$args['name_full'] = $plugin_data['Name'];		$name_short = str_replace( CTC_NAME . ' - ', '', $args['name_full'] ); // with "Church Theme Content - " prefix removed		// Plugin author		$author = strip_tags( $plugin_data['Author'] );		// Apply defaults		$args = wp_parse_args( $args, array(			// Add-on Information			'plugin_file'				=> '',								// Required; absolute path to plugin-name/plugin-name.php; use __FILE__ when in main file (Required)			'plugin_file_base'			=> '',								// This will auto-fill (relative path to main plugin file)			'plugin_dir'				=> '',								// This will auto-fill (plugin's directory name)			'name'						=> $name_short,						// Shown on license key settings and notices; default is plugin name with "Church Theme Content - " prefix removed			'name_full'					=> '',								// This will auto-fill (full name of plugin)			// EDD Software Licensing			'store_url'					=> '',								// URL of store running EDD with Software Licensing extension			'version'					=> $plugin_data['Version'],			// current version of the add-on plugin; default is to auto-determine			'item_name'					=> $name_short,						// must match download's name in EDD store			'author'					=> $author,							// default is to auto-determine from add-on plugin			'updates'					=> true,							// default true; enable one-click updates			'expiring_soon_days'		=> 30,								// days before expiration to consider a license "expiring soon"			'renewal_url'				=> '',								// optional URL for renewal links (ie. EDD checkout); {license_key} will be replaced with key			'renewal_info_url'			=> '',								// optional URL for renewal information			'changelog_url'				=> '',								// optional URL for external changelog			'activate_success_notice'	=> wp_kses( __( '<strong>License key activated.</strong>', 'church-theme-content' ), array( 'strong' => array() ) ),			'activate_fail_notice'		=> wp_kses( __( '<strong>License key could not be activated.</strong>', 'church-theme-content' ), array( 'strong' => array() ) ),			'deactivate_success_notice'	=> wp_kses( __( '<strong>License key deactivated.</strong>', 'church-theme-content' ), array( 'strong' => array() ) ),			'deactivate_fail_notice'	=> wp_kses( __( '<strong>License key could not be deactivated.</strong>', 'church-theme-content' ), array( 'strong' => array() ) ),			'inactive_notice'			=> wp_kses( __( '<strong>Add-on License Inactive:</strong> <a href="%1$s">Activate Your Add-on License</a> to enable updates for <strong>%2$s</strong>.', 'church-theme-content' ), array( 'strong' => array(), 'a' => array( 'href' => array() ) ) ),			'expired_notice'			=> wp_kses( __( '<strong>Add-on License Expired:</strong> <a href="%1$s">Renew Your Add-on License</a> for <strong>%2$s</strong> to re-enable updates (expired on <strong>%3$s</strong>).', 'church-theme-content' ), array( 'strong' => array(), 'a' => array( 'href' => array() ) ) ),			'expiring_soon_notice'		=> wp_kses( __( '<strong>Add-on License Expiring Soon:</strong> <a href="%1$s">Renew Your Add-on License</a> for <strong>%2$s</strong> to continue receiving updates (expires on <strong>%3$s</strong>).', 'church-theme-content' ), array( 'strong' => array(), 'a' => array( 'href' => array() ) ) ),		) );		// Add add-on to global array		$ctc_add_ons[$args['plugin_dir']] = $args;	}}