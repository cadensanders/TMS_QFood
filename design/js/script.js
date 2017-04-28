
/*
 * AIT WordPress Theme
 *
 * Copyright (c) 2012, Affinity Information Technology, s.r.o. (http://ait-themes.com)
 */
/* Main Initialization Hook */
jQuery(document).ready(function(){
	gm_authFailure = function(){
		var apiBanner = document.createElement('div');
		var a = document.createElement('a');
		var linkText = document.createTextNode("Read more");
		a.appendChild(linkText);
		a.title = "Read more";
		a.href = "https://www.ait-themes.club/knowledge-base/google-maps-api-error/";
		a.target = "_blank";

		apiBanner.className = "alert alert-info";
		var bannerText = document.createTextNode("Please check Google API key settings");
		apiBanner.appendChild(bannerText);
		apiBanner.appendChild(document.createElement('br'));
		apiBanner.appendChild(a);

		jQuery(".google-map-container").html(apiBanner);
	};

	// var reset = require('./libs');
	// console.log(reset);
	// jQuery('.dragscroll').each(function(){
	// 	console.log(jQuery(this));
	// });

	/* menu.js initialization */
	desktopMenu();
	responsiveMenu();
	/* menu.js initialization */

	relocateSiteTools();
	showSiteTools();

	/* custom.js initialization */
	touchFriendlyHover([
		".reviews-container .review-rating-overall",
		".taxonomy-menu-container:before"
	]);

	enableResponsiveToggleAreas(true);

	renameUiClasses();
	removeUnwantedClasses();

	initWPGallery();
	initColorbox();
	initRatings();
	//initInfieldLabels();
	initSelectBox();

	notificationClose();
	initCustomScroll();
	initSwipe();
	disableHoverOnScroll();
	/* custom.js initialization */

	/* Theme Dependent Functions */
	telAnchorMobile();
	/* Theme Dependent Functions */

	quickPriceFilter();

	/* Hide Site Tools on Scroll */

	var didScroll;
	var lastScrollTop = 0;
	var delta = 5;
	var siteToolsHeight = jQuery('.tools-bar').outerHeight() * 2;

	jQuery(window).scroll(function(event){
		didScroll = true;
	});

	setInterval(function() {
		if (didScroll && (!isResponsive(980) || isResponsive(640))) {
			hasScrolled();
			didScroll = false;
		}

		if (lastScrollTop == 0 && jQuery('.tools-bar').hasClass('hidden'))
			jQuery('.tools-bar').removeClass('hidden');
	}, 250);

	function hasScrolled() {
		var st = jQuery(window).scrollTop();

		// If scroll is less than delta
		if(Math.abs(lastScrollTop - st) <= delta)
			return;

		if (st > lastScrollTop && st > siteToolsHeight) {
			// Scroll Down
			jQuery('.tools-bar').addClass('hidden');
		} else {
			// Scroll Up
			if(st + jQuery(window).height() < jQuery(document).height()) {
				jQuery('.tools-bar').removeClass('hidden');
			}
		}

		lastScrollTop = st;
	}

	/* Show Site Tools on Hover */

	function showSiteTools() {
		if (!isResponsive(980) || isResponsive(640)) {
			var st = jQuery(window).scrollTop();

			jQuery('.site-header').hover(function() {
				if(Math.abs(lastScrollTop - st) >= delta && jQuery('.tools-bar').hasClass('hidden')) {
					jQuery('.tools-bar').toggleClass('hidden');
				}
			});
		}
	}

});

jQuery(window).load(function(){
	prepareFitMenu();
	fitMenu();
});

jQuery(window).resize(function(){
	relocateSiteTools();
	fitMenu();
	recalcMenuHeight();
});

/* Main Initialization Hook */

/* Theme Dependenent Functions */


function getLatLngFromAddress(address){
	var geocoder = new google.maps.Geocoder();
	geocoder.geocode({'address': address}, function(results, status){
		console.log(status);
		console.log(results[0].geometry.location);
		return results[0].geometry.location;
	});

}


function telAnchorMobile(){
	if (isUserAgent('mobile')) {
		jQuery("a.phone").each(function() {
			this.href = this.href.replace(/^callto/, "tel");
		});
	}
}


function quickPriceFilter(){
	jQuery('#masthead .tools-bar .price-rating').raty({
			font		: true,
			hints		: false,
			readOnly	: false,
			halfShow	: false,
			starOff		: 'fa-usd off',
			starOn		: 'fa-usd',
			click		: function(price){
				quickMapFilter();
			},
			scoreName	: 'price',
		});
}


function filterMapByPrice(price) {
	if (typeof window.globalMaps === "undefined") {
		return;
	}
	var map = window.globalMaps.headerMap;
	var filteredMarkers = [];
	map.clear();

	for (var i in map.markers) {
		if (map.markers[i].data.price == price) {
			if (jQuery.inArray(map.markers[i], filteredMarkers) < 0) {
				filteredMarkers.push(map.markers[i]);
			}
		}
	}
	map.initMarkers(filteredMarkers);
	map.initClusterer();
}


function quickMapFilter(){
	if (typeof window.globalMaps === "undefined") {
		return;
	}

	var map = window.globalMaps.headerMap;
	var filteredMarkers = map.markers;
	map.clear();

	/* FILTER BY CATEGORIES */
	var catFilters = [];
	jQuery('.elm-taxonomy-menu').find('.tax-menu-filter.active').each(function(){
		var currentCatFilter = jQuery(this).data('cat-id');
		catFilters.push(parseInt(currentCatFilter));
	});
	// console.log("category filters: ");
	// console.log(catFilters);
	if (catFilters.length !== 0) {
		var filteredByCat = [];
		for (var i in filteredMarkers) {
			for (var j in catFilters) {
				if (jQuery.inArray(catFilters[j], filteredMarkers[i].data.categories) > -1) {
					if (jQuery.inArray(filteredMarkers[i], filteredByCat) < 0) {
						filteredByCat.push(filteredMarkers[i]);
					}
				}
			}
		}
		filteredMarkers = filteredByCat;
	}


	/* FILTER BY PRICE */
	var price = jQuery("#masthead .price-rating").find('input[name=price]').val();
	if(typeof price !== "undefined" && price != 0) {
		var filteredByPrice = [];
		for (var k in filteredMarkers) {
			if (filteredMarkers[k].data.price == price) {
				filteredByPrice.push(filteredMarkers[k]);
			}
		}
		filteredMarkers = filteredByPrice;
	}

	map.initMarkers(filteredMarkers);
	map.initClusterer();
}

/* Theme Dependenent Function */
