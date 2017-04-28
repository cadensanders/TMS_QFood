<script id="{$htmlId}-script">
//jQuery(window).load(function(){
jQuery(document).ready(function(){
	{if $options->theme->general->progressivePageLoading}
		if(!isResponsive(1024)){
			jQuery("#{!$htmlId}-main").waypoint(function(){
				jQuery("#{!$htmlId}-main").addClass("load-finished");
			}, { triggerOnce: true, offset: "95%" });
		} else {
			jQuery("#{!$htmlId}-main").addClass('load-finished');
		}
	{else}
		jQuery("#{!$htmlId}-main").addClass("load-finished");
	{/if}

	/**/

	var select2Settings = {
		dropdownParent: jQuery('#{!$htmlId}'),
	};

	jQuery('#{!$htmlId}').find('select').select2(select2Settings).on("select2:close", function() {
		// fired to the original element when the dropdown closes
		jQuery('.select2-drop').removeClass('select2-drop-active');

		// replace all &nbsp;
		var regPattern = "&nbsp;";
		jQuery('#{!$htmlId} .category-search-wrap .select2-selection__rendered').html(jQuery('#{!$htmlId} .category-search-wrap .select2-selection__rendered').html().replace(new RegExp(regPattern, "g"), ''));
		if(jQuery('#{!$htmlId} .location-search .select2-chosen').length > 0) {
			jQuery('#{!$htmlId} .location-search .select2-chosen').html(jQuery('#{!$htmlId} .location-search .select2-chosen').html().replace(new RegExp(regPattern, "g"), ''));
		}
	});

	jQuery('#{!$htmlId}').find('select').select2(select2Settings).on("select2-loaded", function() {
		jQuery('#{!$htmlId}').find('.select2-container').removeAttr('style');
	});

	if(isMobile()){
		jQuery('#{!$htmlId} .category-search-wrap').find('select').select2(select2Settings).on("select2:select", function(event) {
			jQuery('#{!$htmlId}').find('.category-clear').addClass('clear-visible');
		});
		jQuery('#{!$htmlId} .location-search-wrap').find('select').select2(select2Settings).on("select2:select", function(event) {
			jQuery('#{!$htmlId}').find('.location-clear').addClass('clear-visible');
		});
	} else {
		jQuery('#{!$htmlId}').find('.category-search-wrap').hover(function(){
			if(jQuery(this).find('select').val().length != 0){
				jQuery(this).find('.category-clear').addClass('clear-visible');
			}
		},function(){
			if(jQuery(this).find('select').val() != ""){
				jQuery(this).find('.category-clear').removeClass('clear-visible');
			}
		});

		if(jQuery('#{!$htmlId} .location-search .select2-chosen').length > 0) {
			jQuery('#{!$htmlId}').find('.location-search-wrap').hover(function(){
				if(jQuery(this).find('select').val().length != 0){
					jQuery(this).find('.location-clear').addClass('clear-visible');
				}
			},function(){
				if(jQuery(this).find('select').val() != ""){
					jQuery(this).find('.location-clear').removeClass('clear-visible');
				}
			});
		}

		jQuery('#{!$htmlId}').find('.price-search-wrap').hover(function(){
			if(jQuery(this).find('input').val().length != 0){
				jQuery(this).find('.price-clear').addClass('clear-visible');
			}
		},function(){
			if(jQuery(this).find('input').val() != ""){
				jQuery(this).find('.price-clear').removeClass('clear-visible');
			}
		});
	}




	jQuery('#{!$htmlId}').find('.category-clear').click(function(e){
		jQuery('#{!$htmlId}').find('.category-search-wrap select').select2("val", "");
		jQuery(this).removeClass('clear-visible');
	});
	jQuery('#{!$htmlId}').find('.location-clear').click(function(e){
		jQuery('#{!$htmlId}').find('.location-search-wrap select').select2("val", "");
		jQuery(this).removeClass('clear-visible');
	});
	jQuery('#{!$htmlId}').find('.price-clear').click(function(e){
		e.stopPropagation();
		jQuery('#{!$htmlId}').find('.price-search-wrap .price-rating').raty("cancel", false);
		jQuery('#{!$htmlId} .price-search-wrap').find('input').each(function(){
			jQuery(this).attr('disabled', 'disabled');
		});
		jQuery(this).removeClass('clear-visible');
	});



	/* RADIUS SCRIPT */
	var lat,
		lon,
		tmp = [];
	window.location.search
	//.replace ( "?", "" )
	// this is better, there might be a question mark inside
	.substr(1)
	.split("&")
	.forEach(function (item) {
		tmp = item.split("=");
		if (tmp[0] === 'lat'){
			lat = decodeURIComponent(tmp[1]);
		}
		if (tmp[0] === 'lon'){
			lon = decodeURIComponent(tmp[1]);
		}
	});
	var coordinatesSet = false;
	if(typeof lat != 'undefined' & typeof lon != 'undefined') {
		coordinatesSet = true;
	}

	var $headerMap = jQuery("#{!$elements->unsortable[header-map]->getHtmlId()}-container");

	var $radiusContainer = jQuery('#{!$htmlId} .radius');
	var $radiusToggle = $radiusContainer.find('.radius-toggle');
	var $radiusDisplay = $radiusContainer.find('.radius-display');
	var $radiusPopup = $radiusContainer.find('.radius-popup-container');

	$radiusToggle.click(function(){
		jQuery(this).removeClass('radius-input-visible').addClass('radius-input-hidden');
		$radiusContainer.find('input').each(function(){
			jQuery(this).removeAttr('disabled');
		});
		$radiusDisplay.removeClass('radius-input-hidden').addClass('radius-input-visible');
		$radiusDisplay.trigger('click');

		$radiusDisplay.find('.radius-value').html($radiusPopup.find('input').val());
		$radiusPopup.find('.radius-value').html($radiusPopup.find('input').val());
	});

	$radiusDisplay.click(function(){
		$radiusPopup.removeClass('radius-input-hidden').addClass('radius-input-visible');

		if(!coordinatesSet) {
			setGeoData();
		}
	});
	$radiusPopup.find('.radius-clear').click(function(e){
		e.stopPropagation();
		$radiusDisplay.removeClass('radius-input-visible').addClass('radius-input-hidden');
		$radiusContainer.find('input').each(function(){
			jQuery(this).attr('disabled', true);
		});
		$radiusPopup.find('.radius-popup-close').trigger('click');
		$radiusToggle.removeClass('radius-input-hidden').addClass('radius-input-visible');
	});
	$radiusPopup.find('.radius-popup-close').click(function(e){
		e.stopPropagation();
		$radiusPopup.removeClass('radius-input-visible').addClass('radius-input-hidden');
	});
	$radiusPopup.find('input').change(function(){
		$radiusDisplay.find('.radius-value').html(jQuery(this).val());
		$radiusPopup.find('.radius-value').html(jQuery(this).val());
	});

	{if $selectedRad}
		$radiusToggle.trigger('click');
	{/if}
	/* RADIUS SCRIPT */




	/* PRICE SCRIPT */
	initPriceRatings();
	var $priceContainer = jQuery('#{!$htmlId} .price-search-wrap');
	var $priceToggle = $priceContainer.find('.price-toggle');
	var $priceDisplay = $priceContainer.find('.price-display');
	var $pricePopup = $priceContainer.find('.price-popup-container');

	$priceContainer.click(function(){
		$priceToggle.removeClass('price-input-visible').addClass('price-input-hidden');
		$priceContainer.find('input').each(function(){
			jQuery(this).removeAttr('disabled');
		});
		$priceDisplay.removeClass('price-input-hidden').addClass('price-input-visible');
		$pricePopup.removeClass('price-input-hidden').addClass('price-input-visible');

		$priceDisplay.find('.price-value').html($pricePopup.find('input').val());
		$pricePopup.find('.price-value').html($pricePopup.find('input').val());
	});

	$pricePopup.find('.price-clear').click(function(e){
		e.stopPropagation();
		$priceDisplay.removeClass('price-input-visible').addClass('price-input-hidden');
		$priceContainer.find('input').each(function(){
			jQuery(this).attr('disabled', true);
		});
		$pricePopup.find('.price-popup-close').trigger('click');
		$priceToggle.removeClass('price-input-hidden').addClass('price-input-visible');
	});
	$pricePopup.find('.price-popup-close').click(function(e){
		e.stopPropagation();
		$pricePopup.removeClass('price-input-visible').addClass('price-input-hidden');
	});


	{if $selectedPrice}
	$priceToggle.removeClass('price-input-visible').addClass('price-input-hidden');
	$priceDisplay.removeClass('price-input-hidden').addClass('price-input-visible');
	{else}
	// raty.js remove all disabled attrs on initialisation
	// so we have to add those attributes again
	$priceContainer.find('input').each(function(){
		jQuery(this).attr('disabled', true);
	});
	{/if}

	function initPriceRatings(){
		jQuery('.price-search-wrap .price-rating').raty({
			font		: true,
			hints		: false,
			readOnly	: false,
			halfShow	: false,
			starOff		: 'fa-usd off',
			starOn		: 'fa-usd',
			score		: function(){
				return (jQuery(this).attr('data-rating'));
			},
			scoreName: 'price',
		});
	}

	/* PRICE SCRIPT */

	/* AUTO GROW SEARCH INPUT */

	{if $type == 2}

	var $searchInput = jQuery('#searchinput-text');

	jQuery(document).one('search-recalc', searchAutoGrow());
	searchAutoGrow();

	function searchAutoGrow() {
		var $searchInputWidth = $searchInput.width(),
			$searchInputMaxWidth = $searchInput.closest('.elm-wrapper').width() - $searchInput.parent().width() + $searchInputWidth - 10;

		if (isResponsive(640)) {
			$searchInputMaxWidth = $searchInput.parent().width();
		}

		$searchInput.autoGrowInput({
			minWidth: $searchInputWidth,
			maxWidth: $searchInputMaxWidth,
			comfortZone: 0
		});

		$searchInput.trigger('search-recalc');
	}

	{/if}

});

jQuery(document).on('toggle-search', function(){
	if(isResponsive(640)) {
		if(jQuery('#toggle-search').hasClass('active')) {
			jQuery('.page-title').addClass('search-visible');
		} else {
			jQuery('.page-title').removeClass('search-visible');
		}
	}
});

 jQuery(document).on('toggle-map', function(){
	if(isResponsive(640)) {
		jQuery('.page-title').removeClass('search-visible');
	}
});

function setGeoData() {
	if(navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(function(position) {
			var pos = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
			jQuery("#latitude-search").attr('value', pos.lat());
			jQuery("#longitude-search").attr('value', pos.lng());
		});
	}
}
</script>
