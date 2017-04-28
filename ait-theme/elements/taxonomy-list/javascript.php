<script id="{$htmlId}-script" type="text/javascript">
jQuery(window).load(function(){
	{if $options->theme->general->progressivePageLoading}
		if(!isResponsive(1024)){
			jQuery("#{!$htmlId}-main").waypoint(function(){
				jQuery("#{!$htmlId}-main").addClass('load-finished');
			}, { triggerOnce: true, offset: "95%" });
		} else {
			jQuery("#{!$htmlId}-main").addClass('load-finished');
		}
	{else}
		jQuery("#{!$htmlId}-main").addClass('load-finished');
	{/if}
});

jQuery(document).ready(function(){
	var $tabs = jQuery("#{!$htmlId}-main .cat-tabs-menu a");
	var $contents = jQuery("#{!$htmlId}-main .cat-tabs-contents");
	var activeClass = "cat-option-active";
	$tabs.each(function(){
		jQuery(this).click(function(e){
			e.preventDefault();
			$tabs.each(function(){
				jQuery(this).removeClass(activeClass);
			});
			$contents.find(".cat-tabs-content").each(function(){
				jQuery(this).removeClass(activeClass);
			});
			jQuery(this).addClass(activeClass);
			$contents.find(".cat-tabs-content:eq("+jQuery(this).index()+")").addClass(activeClass);
		});
	});
});
</script>
