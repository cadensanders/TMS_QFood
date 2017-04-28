{var $ratingsDisplayedClass = AitItemReviews::hasReviewQuestions($post->id) ? 'ratings-shown' : 'ratings-hidden'}

{var $ratingFormShownClass = 'rating-form-shown'}
{if $options->theme->itemReviews->onlyRegistered}
	{if !is_user_logged_in()}
		{var $ratingFormShownClass = 'rating-form-hidden'}
	{/if}
{/if}

<div class="reviews-container {$ratingsDisplayedClass} {$ratingFormShownClass}" id="review">
	{var $reviews_query = AitItemReviews::getCurrentItemReviews($post->id)}

	{if $options->theme->itemReviews->onlyRegistered}
		{if is_user_logged_in()}
			<h2><span class="review-count">{count($reviews_query->posts)}</span>{__ 'Reviews'}</h2>
			{includePart portal/parts/single-item-reviews-form}
		{else}
			<h2><?php _e('Leave a Review', 'ait') ?></h2>
			<div class="current-rating-container review-stars-container">
				<h3>{!$options->theme->itemReviews->onlyRegisteredMessage}</h3>
			</div>
		{/if}
	{else}
		<h2><span class="review-count">{count($reviews_query->posts)}</span>{__ 'Reviews'}</h2>
		{includePart portal/parts/single-item-reviews-form}
	{/if}

	{if $reviews_query->posts > 0}
	<div class="content">
		{customLoop from $reviews_query as $review}

		{var $rating_overall = get_post_meta($review->id, 'rating_mean', true)}
		{var $rating_data = (array)json_decode(get_post_meta($review->id, 'ratings', true))}

		{var $ratingsDisplayedClass = AitItemReviews::willRatingsDisplay($rating_data) ? 'ratings-shown' : 'ratings-hidden'}

		{var $dateFormat = get_option('date_format')}

		<div class="review-container {$ratingsDisplayedClass}">
			<div class="review-info">
				<span class="review-name">{$review->title}</span>
				<span class="review-time"><span>{$review->rawDate|dateI18n: $dateFormat}</span>&nbsp;<span>{$review->time()}</span></span>
				{if is_array($rating_data) && count($rating_data) > 0}
				<div class="review-stars">
					<span class="review-rating-overall" data-score="{$rating_overall}"></span>
					<div class="review-ratings">
						{foreach $rating_data as $index => $rating}
							{if $rating->question}
							<div class="review-rating">
								<span class="review-rating-question">
									{$rating->question}
								</span>
								<span class="review-rating-stars" data-score="{$rating->value}"></span>
							</div>
							{/if}
						{/foreach}
					</div>
				</div>
				{/if}
			</div>
			<div class="content">
				{!$review->content}
			</div>
			{* REVIEW JSON-LD *}
			<script type="application/ld+json">
			{
				"@context": "http://schema.org/",
				"@type": "Review",
				"itemReviewed": {
					"@type": "Thing",
					"name": "{!$post->title}"
				},
				"reviewRating": {
					"@type": "Rating",
					"ratingValue": "{!$rating_overall}"
				},
				"author": {
					"@type": "Person",
					"name": "{!$review->title}"
				},
				"reviewBody": "{!strip_tags($review->content)}"
			}
			</script>
			{* REVIEW JSON-LD *}
		</div>
		{/customLoop}


		<script type="text/javascript">
			jQuery(document).ready(function() {

				/* Review Tooltip Off-screen Check */

				jQuery('#review .review-container .review-stars').mouseenter(function() {
					reviewOffscreen(jQuery(this));
				});

				function reviewOffscreen(rating) {
					var reviewContainer = rating.find('.review-ratings');
					if (!reviewContainer.hasClass('off-screen')) {
						var	bottomOffset = jQuery(document).height() - rating.offset().top - reviewContainer.outerHeight() - 30;
						if (bottomOffset < 0) reviewContainer.addClass('off-screen');
					}
				}
			});
		</script>

	</div>
	{/if}
</div>
