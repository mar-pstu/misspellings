<?php

/**
Plugin Name: Очепятки
Plugin URI: http://pstu.edu/
Description: Плагин оповещения администрации сайта об найденных пользователями ошибках.
Author: PSTU
Version: 2.0.1
Author URI: https://chomovva.ru/
License: GPL2
Text Domain: pstu-misspellings
Domain Path: /languages/
*/


if ( ! defined( 'ABSPATH' ) ) {	exit; };



define( 'PSTU_MISSPELLINGS_INCLUDES', untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/includes/' );
define( 'PSTU_MISSPELLINGS_ASSETS', untrailingslashit( plugin_dir_url(__FILE__) ) . '/assets/' );
define( 'PSTU_MISSPELLINGS_LANGUAGES', dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
define( 'PSTU_MISSPELLINGS_VIEWS', untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/views/' );



require_once PSTU_MISSPELLINGS_INCLUDES . 'class-manager.php';



function run_pstu_misspellings() {
	$manager = new pstuMisspellingsManager( 'pstu_misspellings', '2.0.0', 'pstu-misspellings' );
	$manager->run();
}

run_pstu_misspellings();