<?php

/*
 * AIT WordPress Theme
 *
 * Copyright (c) 2013, Affinity Information Technology, s.r.o. (http://ait-themes.com)
 */

// === Usefull debugging constants ===================================

// if(!defined('AIT_DISABLE_CACHE')) define('AIT_DISABLE_CACHE', true);
// if(!defined('AIT_ENABLE_NDEBUGGER')) define('AIT_ENABLE_NDEBUGGER', true);
define('AIT_THEME_TYPE', 'directory');


// === Loads AIT WordPress Framework ================================
require_once get_template_directory() . '/ait-theme/@framework/load.php';


// === Mandatory WordPress Standard functionality ===================

if(!isset($content_width)) $content_width = 1200;


// === Custom filters, actions for framework overrides ==============
require_once aitPath('includes', '/ait-custom-functions.php');

/* THEME UPGRADE FUNCTIONS */
require_once aitPath('includes', '/ait-theme-upgrades.php');
/* THEME UPGRADE FUNCTIONS */

// === Run the theme ===============================================

$themeConfiguration = include aitPath('config', '/@theme-configuration.php');

AitTheme::run($themeConfiguration);


// === Custom settings ==============================================

// Change number of related products on product page
// Set your own value for 'posts_per_page'
add_filter( 'woocommerce_output_related_products_args', 'ait_related_products_args' );
function ait_related_products_args( $args ) {
	$args['posts_per_page'] = 3; // 3 related products
	$args['columns'] = 3; // arranged in 2 columns
	return $args;
}

add_filter('loop_shop_columns', create_function('', 'return 3;'));

// Display 6 products per page
add_filter('loop_shop_per_page', create_function( '$cols', 'return 6;' ), 20);

if ( aitIsPluginActive( "woocommerce" ) ) {
	add_action('ait-theme-activation', 'woocommerce_image_sizes', 1);
	function woocommerce_image_sizes(){
		update_option( 'shop_catalog_image_size', array('width' => '300', 'height' => '300', 'crop' => 1) );
		update_option( 'shop_single_image_size', array('width' => '600', 'height' => '600', 'crop' => 1) );
		update_option( 'shop_thumbnail_image_size', array('width' => '180', 'height' => '180', 'crop' => 1) );
	}
}

// Disable woocommerce default styles
if ( aitIsPluginActive( "woocommerce" ) ) {
	if ( version_compare( WOOCOMMERCE_VERSION, "2.1" ) >= 0 ) {
		add_filter( 'woocommerce_enqueue_styles', '__return_false' );
	} else {
		define( 'WOOCOMMERCE_USE_CSS', false );
	}
}

function updatePageBuilderOptions(){
	global $wpdb;
	$query = $wpdb->get_results("SELECT option_id, option_value FROM `".$wpdb->prefix."options` WHERE `option_name` LIKE '%_ait_cityguide_elements_opts_%'");
	if(!empty($query)){
		foreach($query as $key => $value){
			$id = $value->option_id;
			$val = unserialize($value->option_value);

			foreach($val as $k => $v){
				if(!empty($v['header-map'])){
					if(!is_array($v['header-map']['address'])){
						$old = $v['header-map']['address'];
						$val[$k]['header-map']['address'] = array(
							"address" => $old,
							"latitude" => 1,
							"longitude" => 1,
							"streetview" => false,
							"swheading" => 90,
							"swpitch" => 5,
							"swzoom" => 1
						);
					}

				}
			}

			$newVal = serialize($val);
			$sql = "UPDATE ".$wpdb->prefix."options SET option_value = '".$newVal."' WHERE option_id = ".$id;
			$wpdb->query($sql);
		}
	}
}

add_action( 'ait-after-import', 'updatePageBuilderOptions', 10, 0 );
add_action( 'ait-theme-upgrade', 'updatePageBuilderOptions', 10, 0 );

function updatePackageSlugs($whatToImport, $sendResults){
	if($whatToImport == "demo-content"){
		$theme = sanitize_key( get_stylesheet() );
		$themeOptionKey = '_ait_'.$theme.'_theme_opts';	// better way to do this
		wp_cache_delete('alloptions', 'options'); // will force to load new options from DB in next get_option call
		$themeOptions = get_option($themeOptionKey, array());

		if(isset($themeOptions['packages']['packageTypes'])){
			foreach($themeOptions['packages']['packageTypes'] as $index => $package){
				$themeOptions['packages']['packageTypes'][$index]['slug'] = str_replace(".", "", uniqid("", true));
			}
			update_option($themeOptionKey, $themeOptions);
		}
	}
}
add_action( 'ait-after-import', 'updatePackageSlugs', 10, 2 );

// === Portal settings =============================================


require_once aitPath('theme', '/portal/functions/portal.php');
require_once aitPath('theme', '/portal/functions/packages.php');
require_once aitPath('theme', '/portal/functions/accounts.php');
require_once aitPath('theme', '/portal/functions/payments.php');

// Lost Password Login Form
add_action('login_form_middle', 'add_lost_password_link');

function add_lost_password_link() {
	$anchor = '<a href="'.wp_lostpassword_url(get_permalink()).'" class="lost-password" title="'.__('Lost Password?', 'ait').'">'.__('Lost Password?', 'ait').'</a>';
	return $anchor;
}

function getDefaultSocialIconColor($id){
	 $defaultIconColors = array(
		'fa-twitter-square' => '#00aced',
		'fa-facebook-square' => '#3b5998',
		'fa-linkedin-square' => '#007bb6',
		'fa-github-square' => '#4183c4',
		'fa-twitter' => '#00aced',
		'fa-facebook' => '#3b5998',
		'fa-github' => '#4183c4',
		'fa-pinterest' => '#cb2027',
		'fa-linkedin' => '#007bb6',
		'fa-pinterest-square' => '#cb2027',
		'fa-google-plus-square' => '#dd4b39',
		'fa-google-plus' => '#dd4b39',
		'fa-github-alt' => '#4183c4',
		'fa-youtube-square' => '#bb0000',
		'fa-youtube' => '#bb0000',
		'fa-youtube-play' => '#bb0000',
		'fa-dropbox' => '#007ee5',
		'fa-stack-overflow' => '#fe7a15',
		'fa-instagram' => '#517fa4',
		'fa-flickr' => '#ff0084',
		'fa-bitbucket' => '#205081',
		'fa-bitbucket-square' => '#205081',
		'fa-tumblr' => '#32506d',
		'fa-tumblr-square' => '#32506d',
		'fa-dribbble' => '#ea4c89',
		'fa-skype' => '#00aff0',
		'fa-foursquare' => '#0072b1',
		'fa-vk'	=> '#45668e',
		'fa-vimeo-square' => '#aad450',
		'fa-wordpress' => '#21759b',
		'fa-reddit' => '#ff4500',
		'fa-reddit-square' => '#ff4500',
		'fa-stumbleupon-circle' => '#eb4823',
		'fa-stumbleupon' => '#eb4823',
		'fa-delicious' => '#3399ff',
		'fa-digg' => '#000000',
		'fa-behance' => '#1769ff',
		'fa-behance-square' => '#1769ff',
		'fa-deviantart' => '#05cc47',
		'fa-soundcloud' => '#ff3a00',
		'fa-vine' => '#00bf8f',
		'fa-slack'	=> '#3db890',
		'fa-lastfm'	=> '#d51007',
		'fa-lastfm-square'	=> '#d51007',
	);
	return isset($defaultIconColors[$id]) ? $defaultIconColors[$id] : '#000000';
}

