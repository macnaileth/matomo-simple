<?php
/*
Plugin Name: Matomo Simple
Description: Simple plugin for integration of Matomo into WordPress when using a local Matomo instance
Version: 1.0.0
Author: Marco Nagel
Author URI: https://tsu-nami.de/
Text Domain: tsu-matomo-simple
Domain Path: /languages
*/
defined( 'ABSPATH' ) or die( 'Are you ok?' );

//get version
$version = get_file_data(__FILE__, ['Version' => 'Version'], 'plugin');

//global plugin paths
define( 'TSU_MS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'TSU_MS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'TSU_MS_VERSION', $version['Version'] );

//Plugin header translation strings
esc_html__( 'Matomo Simple', 'tsu-matomo-simple' );
esc_html__( 'Simple plugin for integration of Matomo into WordPress when using a local Matomo instance', 'tsu-matomo-simple' );

//startup
require_once TSU_MS_PLUGIN_PATH . '/lib/TSUMSCore.php';
load_plugin_textdomain( 'tsu-matomo-simple', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 

$start_matomo_simple  = new lib\TSUMSCore();

