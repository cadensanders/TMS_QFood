{var $meta = $item->meta(item-data)}
{var $imageUrl = $item->hasImage ? $item->imageUrl : $options->theme->item->noFeatured}
<div class="item-header">
	<img src="{imageUrl $imageUrl, width => 280, height => 105, crop => 1}" alt="{!$item->title}">
	<div class="item-title">
		{if defined('AIT_FOOD_MENU_ENABLED')}
			{var $priceRating = get_post_meta($item->id, '_ait-item_food-options_price', true)}
			{includePart parts/food-menu-price-rating, class => "header", rating => $priceRating}
		{/if}
		<h3><a href="{$item->permalink}">{!$item->title}</a></h3>
		<div class="item-footer {if defined('AIT_REVIEWS_ENABLED')}reviews-enabled{/if}">
			<p class="item-address">{!$meta->map['address']}</p>
			{if defined('AIT_REVIEWS_ENABLED')}
				<div class="item-rating">{includePart "portal/parts/carousel-reviews-stars", item => $item, showCount => false}</div>
			{/if}
		</div>
	</div>
</div>
<div class="item-data">
	<div class="scroll">
	{if defined('AIT_FOOD_MENU_ENABLED')}
		{var $featuredMenuType = $options->theme->foodMenu->featuredMenuType}
		{var $query = AitFoodMenu::getItemSpecialMenu($item->id, $featuredMenuType, date('Y-m-d'))}
		{if $query->havePosts}
			{includePart portal/parts/special-food-menu-content query => $query}
		{else}
			<div class="item-desc">
				{!$item->excerpt|striptags|trimWords: 15}
			</div>
		{/if}
	{else}
		<div class="item-desc">
			{!$item->excerpt|striptags|trimWords: 15}
		</div>
	{/if}
	</div>
</div>

