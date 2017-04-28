

"use strict";


function desktopMenu(){
	if(!isResponsive(1200)){
		// only run at 640px+
		jQuery('.nav-menu-main').find('li').each(function(){
			if(jQuery(this).find('ul.sub-menu').length){
				// item has a submenu
				jQuery(this).addClass('has-submenu');
			}
		});

		popupDesktopMenu();

		androidDesktopMenuFix();
	}
}

function relocateSiteTools() {
	var regularPos = jQuery('.menu-tools');
	var relocatedPos = jQuery('.nav-menu-container');
	var siteToolsSelector = '.site-tools';
	var $siteTools = jQuery(siteToolsSelector);

	if(isResponsive(640)){
		if(regularPos.find('> '+siteToolsSelector).length > 0) {
			var $relocate = $siteTools.detach();
			$relocate.prependTo(relocatedPos);
		}
	} else {
		if(relocatedPos.find('> '+siteToolsSelector).length > 0) {
			var $relocate = $siteTools.detach();
			$relocate.appendTo(regularPos);
		}
	}
}

function responsiveMenu(){

	jQuery('.main-nav .menu-toggle').click(function(){
		if(isResponsive(1200)){
			var $this = jQuery(this),
				scrollPosition = jQuery(window).scrollTop();

			$this.parent().toggleClass('hover');
			jQuery('body').toggleClass('cover');

			if(jQuery('body').hasClass('cover')) {
				jQuery('body').css({'top': '-' + scrollPosition + 'px'});
				jQuery('body').addClass('overflow');
			} else {
				var scrollPosition = parseInt(jQuery('body').css('top'), 10) * (-1);
				jQuery('body').removeClass('overflow');
				jQuery('body').removeAttr("style");
				jQuery('html, body').scrollTop(scrollPosition);
			}

			if (jQuery('.taxonomy-menu-container').hasClass('expanded')) {
				jQuery('.tax-menu-close').trigger('click');
			}

			if(jQuery('body').hasClass('cover')) {
				recalcMenuHeight();
			}

			setTimeout(function() {
				jQuery('.main-nav-wrap').addClass('scroll');
			}, 300);
		}
	});

	if(isResponsive(1200)){
		//iphoneMenuFix();
	}

}

function iphoneMenuFix(){
	if(isUserAgent('iphone')){
		jQuery('.main-nav li a').each(function(){
			jQuery(this).on('click', function(){
				window.location.href = jQuery(this).attr('href');
			});
		});
	}
}

function androidDesktopMenuFix(){
	if(isAndroid() && !isResponsive(1200)){
		jQuery('.nav-menu-main').find('li').each(function(){
			jQuery(this).bind('click', function(e){
				e.stopPropagation();
				if(jQuery(this).hasClass('has-submenu')){
					var itemID = jQuery(this).attr('id');
					if(jQuery(this).hasClass('clicked-once')){
						// the second click on target
					} else {
						// if there was clicked on other item reset all except current
						jQuery('.nav-menu-main').find('li').each(function(){
							if(jQuery(this).attr('id') != itemID){
								jQuery(this).removeClass('clicked-once');
							}
						});
						// first click on target
						e.preventDefault();
						jQuery(this).addClass('clicked-once');
					}
				} else {
					// normal flow
				}
			});
		});
	}
}

function popupDesktopMenu(){

	if(!isResponsive(768)){
		if(jQuery('body').hasClass('sticky-menu-enabled')){

			var stickyMenu = jQuery('.menu-container');
			var stickyOffset = /*stickyMenu.height()*/82;

			if (stickyMenu.next('div').length) {
				stickyMenu.next().css('margin-top', stickyOffset);
			} else {
				jQuery('.page-content').css('margin-top', stickyOffset);
			}
		}
	}
}

function recalcMenuHeight() {
	var menu = jQuery('.nav-menu-main');
	if (isResponsive(1200)) {
		menu.height(jQuery(window).height() - jQuery('.site-header').height() - jQuery('#wpadminbar').height());
	} else {
		menu.removeAttr("style");
	}
}

function prepareFitMenu(){
	var reservedForDropdown = 150;

	var $container = jQuery('.header-container');
	var $navContainer = $container.find('.main-nav');
	var $menuContainer = $navContainer.find('#menu-main-menu');

	$navContainer.css({'display':'inline-block', 'visibility':'hidden'});

	// create the wrapper if missing
	if($menuContainer.find('.menu-item-wrapper').length == 0){
		var containerWidth = $container.width();
		var siteLogoWidth = $container.find('.site-logo').width();
		var siteToolsWidth = $container.find('.site-tools').width();

		var avalaibleSpace = parseInt(containerWidth - (siteLogoWidth + siteToolsWidth));
		avalaibleSpace = avalaibleSpace - reservedForDropdown;

		if(parseInt($navContainer.width()) > avalaibleSpace){
			$menuContainer.append('<li class="menu-item-wrapper"><i class="fa fa-ellipsis-v"></i><ul class="menu-items-wrap"></ul></li>')
		}
	}

	// duplicate the menu to the wrapper
	var $menuWrapper = $menuContainer.find('.menu-items-wrap');
	var $menuContainerChildren = $menuContainer.children('li:not(.menu-item-wrapper)').clone(true);
	$menuContainerChildren.appendTo($menuWrapper);

	$menuWrapper.find('li').each(function(){
		// update id
		var oldId = jQuery(this).attr('id');
		if(typeof oldId !== "undefined"){
			jQuery(this).attr('id', jQuery(this).attr('id')+'-wrapclone');
		}
		// update class
		if(jQuery(this).hasClass('menu-item-has-columns')){
			jQuery(this).removeAttr('class');
			jQuery(this).addClass('menu-item-wrapped menu-item-has-columns');
		} else {
			jQuery(this).removeAttr('class');
			jQuery(this).addClass('menu-item-wrapped');
		}
	});

	var allLiWidth = 0;
	$menuContainer.children('li:not(.menu-item-wrapper)').each(function(){
		var width = jQuery(this).width();
		jQuery(this).attr('data-width', width);
		allLiWidth = allLiWidth + width;
	});
	$menuContainer.attr('data-liWidth', allLiWidth);

	clearMegamenu();
	menuAddClasses();

	$menuContainer.children('li.menu-item-wrapper').hide();

	$navContainer.css({'visibility':'visible'});
}


function fitMenu(){
	var $container = jQuery('.header-container');
	var $navContainer = $container.find('.main-nav');
	var $menuContainer = $navContainer.find('#menu-main-menu');

	if(!isResponsive(1200)){
		// show button
		var containerWidth = $container.width();
		var siteLogoWidth = $container.find('.site-logo').width();
		var siteToolsWidth = $container.find('.site-tools').width();
		var avalaibleSpace = parseInt(containerWidth - (siteLogoWidth + siteToolsWidth));

		var reservedForDropdown = 150;
		avalaibleSpace = avalaibleSpace - reservedForDropdown;

		if(parseInt($menuContainer.attr('data-liWidth')) > avalaibleSpace){
			// the menu is bigger than the space
			var fittingWidth = avalaibleSpace;
			//var lastFittableLi = null;
			$navContainer.find('ul#menu-main-menu').children('li:not(.menu-item-wrapper)').each(function(){
				// for every li, get his width and try to fit it
				var liWidth = parseInt(jQuery(this).attr('data-width')) == 0 ? jQuery(this).width() : parseInt(jQuery(this).attr('data-width'));
				fittingWidth = parseInt(fittingWidth - liWidth);
				var liID = jQuery(this).attr('id');
				if(fittingWidth > 0){
					// no problem .. li fits
					// hide this in the wrapmenu
					jQuery(this).show();
					jQuery('#'+liID+'-wrapclone').hide();
				} else {
					// problem .. li doesnt fit
					// show this in the wrapmenu
					if($menuContainer.find('.menu-item-wrapper').length == 0)
						$menuContainer.append('<li class="menu-item-wrapper"><i class="fa fa-ellipsis-v"></i><ul class="menu-items-wrap"></ul></li>');
					jQuery(this).hide();
					jQuery('#'+liID+'-wrapclone').show();
				}
			});

			$menuContainer.find('.menu-item-wrapper').show();
		} else {
			var fittingWidth = avalaibleSpace;
			$navContainer.find('ul#menu-main-menu').children('li:not(.menu-item-wrapper)').each(function(){
				var liWidth = parseInt(jQuery(this).attr('data-width')) == 0 ? jQuery(this).width() : parseInt(jQuery(this).attr('data-width'));
				fittingWidth = parseInt(fittingWidth - liWidth);
				var liID = jQuery(this).attr('id');
				if(fittingWidth > 0){
					// no problem .. li fits
					// hide this in the wrapmenu
					jQuery(this).show();
					jQuery('#'+liID+'-wrapclone').hide();
				}
			});
			// hide wrapping menu
			$menuContainer.find('.menu-item-wrapper').hide();
		}
	} else {
		// hide button on responsive
		$navContainer.find('ul#menu-main-menu').children('li:not(.menu-item-wrapper)').show();
		$menuContainer.find('.menu-item-wrapper').hide();
	}
}

function clearMegamenu(){
	if(jQuery('ul.menu-items-wrap').find('li.menu-item-has-columns').length > 0){
		jQuery('ul.menu-items-wrap').find('li.menu-item-has-columns ul li').each(function(){
			if(jQuery(this).children('a').length > 0){
				jQuery(this).addClass('leave-me-alone');
			}
		});

		jQuery('ul.menu-items-wrap').find('li.menu-item-has-columns').each(function(){
			var $lis = jQuery(this).find('li.leave-me-alone').clone(true);
			var $con = jQuery(this).find('ul');
			$con.children().remove();
			$con.append($lis);
		});
		jQuery('ul.menu-items-wrap').find('li.leave-me-alone').removeClass('leave-me-alone');
	}
}

function menuAddClasses(){
	jQuery('.menu-items-wrap').find('li').each(function(){
		if(jQuery(this).children('ul').length > 0){
			jQuery(this).addClass('menu-item-has-children');
		}
	});
}
