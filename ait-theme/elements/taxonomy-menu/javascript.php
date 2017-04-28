<script id="{$htmlId}-script">


(function($, $window, $document, globals){
"use strict";

jQuery(document).ready(function(){

	jQuery('.taxonomy-menu-container').hover(function()
		{
			if (isResponsive(980) && !isResponsive(480)) {
				jQuery('body').addClass('overflow');
				recalcHeight();
			}
		}, function() {
			if (isResponsive(980) && !isResponsive(480)) {
				jQuery('body').removeClass('overflow');
				jQuery(this).removeAttr('style');
			}
		}
	);

	jQuery('.taxonomy-menu-container').click(function() {
		if(jQuery('.taxonomy-menu-container').hasClass('hover')) {
			jQuery('body').removeClass('overflow');
			jQuery('.taxonomy-menu-container').removeAttr('style');
			jQuery('.taxonomy-menu-container').removeClass('expanded');
		}
	});

	// apply only on custom pages
	jQuery('#{!$htmlId} .tax-menu-filter').on('click', function(e){
		e.stopPropagation();
		jQuery(this).toggleClass('active');
		quickMapFilter();

	});

	expandTaxMenu();

});

jQuery(window).resize(function() {
	if (jQuery('.taxonomy-menu-container').hasClass('expanded')) {
		recalcHeight();
	}
});


function recalcHeight() {
	if (isResponsive(980)) {
		var taxMenu = jQuery('.taxonomy-menu-container'),
			taxMenuTop = taxMenu[0].getBoundingClientRect().top,
			windowHeight = jQuery(window).height();

		taxMenu.height(windowHeight - taxMenuTop);
	}
}

function expandTaxMenu() {
	var buttonOn = jQuery('.tax-menu-expand');
	var buttonOff = jQuery('.tax-menu-close');

	buttonOn.on('click', function() {
		if (isResponsive(480)) {
			if (jQuery('.main-nav-wrap').hasClass('hover')) {
				jQuery('.main-nav .menu-toggle').trigger('click');
			}

			jQuery('body').addClass('overflow');
			recalcHeight();
			jQuery('.taxonomy-menu-container').addClass('expanded');
		}

	});

	buttonOff.on('click', function(e) {
		e.preventDefault();
		if (isResponsive(480)) {
			jQuery('body').removeClass('overflow');
			jQuery('.taxonomy-menu-container').removeAttr('style');
			jQuery('.taxonomy-menu-container').removeClass('expanded');
			jQuery('.taxonomy-menu-container').removeClass('hover');
		}
		/*setTimeout(function() {
			jQuery('.taxonomy-menu-container').removeClass('fixed');
		}, 400);*/
	});
}

})(jQuery, jQuery(window), jQuery(document), this);
</script>
