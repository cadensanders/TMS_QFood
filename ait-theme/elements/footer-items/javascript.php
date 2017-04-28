{if $options->layout->custom->layout == 'full'}
	<script id="{$htmlId}-script">

		jQuery(window).load(function(){

			recalcHeaderMap();

		});

		/* Recalculate Hieght of Header Map When Set to Auto and Footer Items are Enabled */

		function recalcHeaderMap() {
			if (!isResponsive(768) && jQuery(window).height() >= 768) {
				if (jQuery(".elm-header-map .google-map-container.auto") && jQuery("#main").height() == 0) {

					{if !$options->layout->general->enableFooterBar}
					jQuery('body').addClass('overflow');
					{/if}

					var siteHeaderHeight = jQuery(".site-header").outerHeight(),
						footerItemsHeight = jQuery("#{!$htmlId} .item").height() + 10,
						adminbarHeight = jQuery("#wpadminbar").height(),
						newMapHeight = jQuery(window).height() - siteHeaderHeight - footerItemsHeight - adminbarHeight;

					if (newMapHeight > 0) jQuery(".elm-header-map .google-map-container.auto").height(newMapHeight);
				}
			}
		}

	</script>
{/if}
