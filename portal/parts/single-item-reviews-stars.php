{var $rating_count = AitItemReviews::getRatingCount($post->id)}
{var $rating_mean = floatval(get_post_meta($post->id, 'rating_mean', true))}

{var $showCount = isset($showCount) ? $showCount : false}
<div class="review-stars-container">
	{if $rating_count > 0}
		<div class="content" itemscope itemtype="http://schema.org/AggregateRating">
			{* RICH SNIPPETS *}
			<span style="display: none" itemprop="itemReviewed">{!$post->title}</span>
			<span style="display: none" itemprop="ratingValue">{$rating_mean}</span>
			<span style="display: none" itemprop="ratingCount">{$rating_count}</span>
			{* RICH SNIPPETS *}
			<span class="review-stars" data-score="{$rating_mean}"></span>
			{if $showCount}<span class="review-count">{$rating_count}</span>{/if}
		</div>
	{else}
		<div class="content">
			<span class="review-no-stars">{__ 'No reviews'}</span>
			{if $showCount}<span class="review-count">0</span>{/if}
		</div>
	{/if}
</div>
