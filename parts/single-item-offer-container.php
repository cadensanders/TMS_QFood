{* REQUIRED PARAMETERS *}
{*
	$post - wp entity of Special Offer
	$meta - meta data of Special Offer
*}

{var $offerImg = "default_offer_img.jpg"}
{if $post->image}
	{var $offerImage = $post->imageUrl}
{elseif aitUrl('img', "/$offerImg")}
	{var $offerImage = aitUrl('img', "/$offerImg")}
{elseif file_exists(AitSpecialOffers::getPaths('design')."/img/$offerImg")}
	{var $offerImage = AitSpecialOffers::getPaths('design')."/img/$offerImg"}
{/if}

{var $dateFrom = !empty($meta['dateFrom']) ? date_i18n(get_option('date_format'), strtotime($meta['dateFrom'])) : ''}
{var $dateTo   = !empty($meta['dateTo']) ? date_i18n(get_option('date_format'), strtotime($meta['dateTo'])) : ''}

<div class="columns-grid column-grid-2">
	<div class="column-1 column column-span-1 column-narrow column-first">

		<div class="item-header">
			<div class="img" style="background-image: url('{imageUrl $offerImage, width => 500, height => 280, crop => 1}');"></div>
			<div class="price">{currency $meta['price'], $meta['currency']}</div>
		</div>

		<div class="item-dates">
			<div class="dates-wrap">
				{if $dateFrom}
				<div class="date-from">
					<div class="title"><i class="fa fa-calendar"></i> {__ 'from:'}</div>
					<div class="date">{$dateFrom}</div>
				</div>
				{/if}
				{if $dateTo}
				<div class="date-to">
					<div class="title"><i class="fa fa-calendar"></i> {__ 'to:'}</div>
					<div class="date">{$dateTo}</div>
				</div>
				{/if}
			</div>
		</div>

		<div class="custom-navigation-arrows"></div>
	</div>

	<div class="column-2 column column-span-1 column-narrow column-last">

		<div class="content">
			<div class="item-title">
				<div class="label">{__ 'Special Offer'}</div>
				<h3>{!$post->title}</h3>
			</div>

			<div class="item-data">
				<div class="entry-content">
					{if $post->hasContent}
						{!$post->content}
					{else}
						{!$post->excerpt}
					{/if}
				</div>
			</div>
		</div>

		<div class="item-more disabled">{__ 'Read More'}</div>

	</div>
</div>
