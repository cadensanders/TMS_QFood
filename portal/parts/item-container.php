{var $noFeatured = $options->theme->item->noFeatured}
{var $categories = get_the_terms($post->id, 'ait-items')}
{var $meta = $post->meta('item-data')}

{var $dbFeatured = get_post_meta($post->id, '_ait-item_item-featured', true)}
{var $isFeatured = $dbFeatured != "" ? (bool)$dbFeatured : false}

<div n:class='item-container, $isFeatured ? item-featured, defined("AIT_REVIEWS_ENABLED") ? reviews-enabled'>
	<div class="content">
		<div class="item-image">
			<a href="{$post->permalink}">
				{if $post->image}
				<img src="{imageUrl $post->imageUrl, width => 750, height => 350, crop => 1}" alt="Featured">
				{else}
				<img src="{imageUrl $noFeatured, width => 750, height => 350, crop => 1}" alt="Featured">
				{/if}
				<div class="item-data">
					<div class="item-body">
						<div class="entry-content">
							<p class="txtrows-2">{if $post->hasContent}{!$post->content|striptags|trim|truncate: 140}{else}{!$post->excerpt|striptags|trim|truncate: 140}{/if}</p>
						</div>
					</div>
					<div class="item-footer">
						{if defined('AIT_FOOD_MENU_ENABLED')}
							{var $priceRating = get_post_meta($post->id, '_ait-item_food-options_price', true)}
							{includePart "parts/food-menu-price-rating", rating => $priceRating}
						{/if}
						<div class="item-address">
							{$meta->map['address']}
						</div>
					</div>
				</div>
			</a>
		</div>

		<div class="item-header">
			<div class="item-title">
				<a href="{$post->permalink}"><h3>{!$post->title}</h3></a>
			</div>
			{if defined('AIT_REVIEWS_ENABLED')}
				{includePart "portal/parts/carousel-reviews-stars", item => $post, showCount => false}
			{/if}
			{*if $categories}
			<div class="item-categories">
				{foreach $categories as $category}
					<span class="item-category">{!$category->name}</span>
				{/foreach}
			</div>
			{/if*}
		</div>
	</div>
</div>
