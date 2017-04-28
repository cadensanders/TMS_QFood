{if $meta->displayGallery && count($meta->gallery) > 0}
	{var $gallery = $meta->gallery}
	{var $galleryCount = count($gallery)}
	{var $galleryClass = $galleryCount == 2 ? 'two-thumbs' : ($galleryCount == 3 ? 'three-thumbs' : '')}

	<div class="gallery-container">
		<div class="title">
			<h2>{__ 'Gallery'}</h2>
			<div class="info">
				{var $infoCount = '<span>'.$galleryCount.'</span>'}
				{__ '%s Photos'|printf:$infoCount|noescape}
			</div>
			<div class="arrows">
				<a href="#" class="arrow-left disabled"><i class="fa fa-caret-left"></i></a>
				<a href="#" class="arrow-right"><i class="fa fa-caret-right"></i></a>
			</div>
		</div>

		<div class="gallery-content optiscroll">
			<div class="slider optiscroll-content dragscroll">
				<ul class="thumbnails {$galleryClass}">
					{if $galleryCount == 1}
					{else}
					{/if}
					<!--
					{foreach $gallery as $item}
					{var $title = AitLangs::getCurrentLocaleText($item['title'])}
					--><li class="thumbnail" style="background-image: url('{imageUrl $item['image'], width => 400, crop => 1}');">
							<a href="{$item['image']}" title="{$title}" target="_blank" data-rel="item-gallery">
								<!--<img src="{imageUrl $item['image'], width => 400, crop => 1}" alt="{$title}" />-->
								{if $title != ""}<span class="thumbnail-title">{$title}</span>{/if}
							</a>
						</li><!--
					{/foreach}
				--></ul>
			</div>
		</div>

		<script type="text/javascript">

			jQuery(window).load(function() {

				var arrows = jQuery(".gallery-container .arrows"),
					arrowLeft = arrows.find('.arrow-left'),
					arrowRight = arrows.find('.arrow-right'),
					slider = jQuery(".gallery-content.optiscroll"),
					thumb = slider.find('.thumbnail').first(),
					slideX = 0;

				setTimeout(function() {
					displayArrows(slider, arrows);
				}, 1000);

				arrowLeft.on('click', function(e){
					e.preventDefault();

					thumbWidth = thumb.outerWidth(true);
					maxScroll = getMaxScroll(slider);

					if (maxScroll != (maxScroll - slideX)) {
						slideX = slideX - thumbWidth;
						arrowLeft.removeClass('disabled');
						arrowRight.removeClass('disabled');
					}

					if (maxScroll == (maxScroll - slideX)) {
						arrowLeft.addClass('disabled');
					}

					slider.data('optiscroll').scrollTo(slideX, false);
				});

				arrowRight.on('click', function(e){
					e.preventDefault();

					thumbWidth = thumb.outerWidth(true);
					maxScroll = getMaxScroll(slider);

					if ((maxScroll - slideX) > 0) {
						slideX = slideX + thumbWidth;
						arrowRight.removeClass('disabled');
						arrowLeft.removeClass('disabled');
					}

					if ((maxScroll - slideX) == 0) {
						arrowRight.addClass('disabled');
					}

					slider.data('optiscroll').scrollTo(slideX, false);
				});

				slider.on('resetSlider', function() {
					slideX = 0;
					displayArrows(slider, arrows);
				});

			});

			jQuery(window).resize(function() {
				jQuery(".gallery-content.optiscroll").trigger('resetSlider');
			});

			function getMaxScroll(slider) {
				return slider.find('.slider')[0].scrollWidth - slider.find('.slider')[0].clientWidth;
			}

			function displayArrows(slider, arrows) {
				if (getMaxScroll(slider) > 0) {
					arrows.addClass('visible');
				} else {
					arrows.removeClass('visible');
				}
			}

		</script>
	</div>
{/if}
