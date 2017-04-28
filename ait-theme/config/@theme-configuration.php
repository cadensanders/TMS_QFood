<?php

/*
 * AIT WordPress Theme
 *
 * Copyright (c) 2014, Affinity Information Technology, s.r.o. (http://ait-themes.com)
 */

return array(

	'menus' => array(
		'main'   => __('Main menu', 'ait-admin'),
		'footer' => __('Footer menu', 'ait-admin'),
	),

	// Supported standard WordPress features
	'theme-support' => array(
		'woocommerce',
		'automatic-feed-links',
		'post-thumbnails',
	),

	// Supported custom ait-theme features
	'ait-theme-support' => array(
        'ait-languages-plugin',
		'megamenu',
		'cpts' => array(
			'ad-space',
			'faq',
			'partner',
			'price-table',
			'toggle',
			'item',
		),
		'elements' => array(
			'ait-item-extension',
			'get-directions',
			'advertising-spaces',
			'contact-form',
			'columns',
			'easy-slider',
			'facebook',
			'faq',
			'google-map',
			'header-map',
			'items',
			'items-info',
			'page-title',
			'partners',
			'posts',
			'price-table',
			'revolution-slider',
			'rule',
			'seo',
			'search-form',
			'taxonomy-list',
			'text',
			'toggles',
			'twitter',
			'video',
			'widget-area',
			'taxonomy-menu',
			'footer-items'
		),
	),

	'plugins' => array(
		'ait-toolkit' => array(
			'required' => true,
			'name'     => 'AIT Elements Toolkit',
		),
		'ait-shortcodes' => array(
			'required' => true,
			'name'     => 'AIT Shortcodes',
		),
		'revslider' => array(
			'required' => true,
		),
	),

	'assets' => array(
		'fonts' => array(
			'awesome',
			'roboto' => array(
				'bold', 'italic', 'regular', 'black',
			),
			'opensans' => array(
				'light', 'regular', 'bold', 'italic', 'semibold',
			),
		),

		'css' => array(
			'jquery-selectbox' => array(
				'file' => '/libs/jquery.selectbox.css',
			),
			/*'jquery-select2' => array(
				'file' => '/libs/jquery.select2-3.5.1.css',
			),*/
			'jquery-select2' => array(
				'file' => '/libs/jquery.select2-4.0.1.css',
			),
			'font-awesome'	=> array(
				'file'	=> '/libs/font-awesome.css',
			),
			'jquery-ui-css' => true,
			'optiscroll'	=> array(
				'file'	=> '/libs/optiscroll.css',
			),
		),
		'js' => array(
			'jquery-selectbox' => array(
				'file' => '/libs/jquery.selectbox-0.2.js',
				'deps' => array('jquery')
			),
			/*'jquery-select2' => array(
				'file' => '/libs/jquery.select2-3.5.1.js',
				'deps' => array('jquery')
			),*/
			'jquery-select2' => array(
				'file' => '/libs/jquery.select2-4.0.1.js',
				'deps' => array('jquery')
			),
			'jquery-raty' => array(
				'file' => '/libs/jquery.raty-2.5.2.js',
				'deps' => array('jquery')
			),
			'jquery-waypoints' => array(
				'file' => '/libs/jquery-waypoints-2.0.3.js',
				'deps' => array('jquery')
			),
			'jquery-infieldlabels' => array(
				'file'	=> '/libs/jquery.infieldlabel-0.1.4.js',
				'deps'	=> array('jquery'),
			),
			'jquery-gmap3-local' => array(
				'file'	=> '/libs/gmap3.min.js',
				'deps'	=> array('jquery', 'googlemaps-api'),
			),
			'jquery-gmap3-infobox-local' => array(
				'file'	=> '/libs/gmap3.infobox.js',
				'deps'	=> array('jquery', 'jquery-gmap3-local'),
			),
			'jquery-optiscroll' => array(
				'file'	=> '/libs/jquery.optiscroll.js',
				'deps'	=> array('jquery'),
			),
			'dragscroll' => array(
				'file'	=> '/libs/dragscroll.js',
			),
			'hammer' => array(
				'file'	=> '/libs/hammer.js',
			),
			'jquery-hammer' => array(
				'file'	=> '/libs/jquery.hammer.js',
				'deps'	=> array('jquery', 'hammer'),
			),
			/* AIT CUSTOM SCRIPTS */
			'ait-mobile-script' => array(
				'file' => '/mobile.js',
				'deps' => array('jquery')
			),
			'ait-menu-script' => array(
				'file' => '/menu.js',
				'deps' => array('jquery', 'ait-mobile-script')
			),
			'ait-custom-script' => array(
				'file' => '/custom.js',
				'deps' => array('jquery', 'ait-mobile-script')
			),
			'ait-woocommerce-script' => array(
				'file' => '/woocommerce.js',
				'deps' => array('jquery'),
				'enqueue-only-if' => '!is_admin() and aitIsPluginActive("woocommerce")',
			),
			/* AIT CUSTOM SCRIPTS */
			'ait-script' => array(
				'file' => '/script.js',
				'deps' => array('jquery', 'ait-mobile-script', 'ait-menu-script', 'ait-custom-script')
			),
			'marker-clusterer' => array(
				'file'      => aitUrl('assets', '/marker-clusterer/markerclusterer-plus.js'),
				'deps'      => array('googlemaps-api', 'ait'),
				'ver'       => '2.1.1',
			),
			'jquery-ui-datepicker' => true,
			'datepicker-translation' => array(
				// datepicker translations are used in admin (= framework) and in frontend - until better file structure is made, keep them in admin assets
				'file'		=> aitPaths()->url->admin . "/assets/libs/datepicker/jquery-ui-i18n.min.js",
				'deps'		=> array('jquery-ui-datepicker'),
				'enqueue-only-if' => 'AitLangs::getCurrentLanguageCode() != "en"',
			),
			'modernizr' => true,
		),
	),

	'frontend-ajax' => array(
		'send-email',
		'contact-owner',
		'login-widget-check-captcha',
	),
);
