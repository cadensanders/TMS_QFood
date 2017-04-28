{block content}

	{loop as $post}
		{* SETTINGS AND DATA *}
		{var $meta = $post->meta('item-data')}
		{var $settings = $options->theme->item}
		{* SETTINGS AND DATA *}

		{*RICH SNIPPET WRAP*}
		<div class="item-content-wrap" itemscope itemtype="http://schema.org/LocalBusiness">
			<meta itemprop="name" content="{$post->title}">
		{*RICH SNIPPET WRAP*}

			{* CATEGORIES AND ADVANCED FILTERS *}
			<div class="categories-bar">
				{if $post->categories('ait-items')}
				<div class="item-categories">
					{foreach $post->categories('ait-items') as $cat}
					<a href="{$cat->url}" class="item-category"><span>{!$cat->title}</span></a>
					{/foreach}
				</div>
				{/if}
			</div>
			{* CATEGORIES AND ADVANCED FILTERS *}

			{* CONTENT SECTION *}
			<div class="entry-content-wrap" itemprop="description">
				<div class="entry-content">
					<div class="entry-content-panel">
						{if defined('AIT_REVIEWS_ENABLED')}
						{includePart portal/parts/single-item-review-add}
						{/if}

						{* SOCIAL SECTION *}
						{includePart portal/parts/single-item-social}
						{* SOCIAL SECTION *}
					</div>

					{if $post->hasContent}
						{!$post->content}
					{else}
						{!$post->excerpt}
					{/if}
				</div>
			</div>
			{* CONTENT SECTION *}

			<div class="column-grid {if $meta->displayOpeningHours}column-grid-2{else}column-grid-1{/if}">
				<div class="column column-span-1 column-narrow column-first">
					{* ADDRESS SECTION *}
					{includePart portal/parts/single-item-address}
					{* ADDRESS SECTION *}

					{* GET DIRECTIONS SECTION *}
					{if defined('AIT_GET_DIRECTIONS_ENABLED')}
						{includePart portal/parts/get-directions-button}
					{/if}
					{* GET DIRECTIONS SECTION *}

					{* CONTACT OWNER SECTION *}
					{includePart portal/parts/single-item-contact-owner}
					{* CONTACT OWNER SECTION *}

					{* SOCIAL ICONS SECTION *}
					{includePart portal/parts/single-item-social-icons}
					{* SOCIAL ICONS SECTION *}
				</div>

				{if $meta->displayOpeningHours}
				<div class="column column-span-1 column-narrow column-last">
					{* OPENING HOURS SECTION *}
					{includePart portal/parts/single-item-opening-hours}
					{* OPENING HOURS SECTION *}
				</div>
				{/if}
			</div>

			{* MAP SECTION *}
			{includePart portal/parts/single-item-map}
			{* MAP SECTION *}

			{* GET DIRECTIONS SECTION *}
			{if defined('AIT_GET_DIRECTIONS_ENABLED')}
				{includePart portal/parts/get-directions-container}
			{/if}
			{* GET DIRECTIONS SECTION *}

			{* ITEM EXTENSION *}
			{if defined('AIT_EXTENSION_ENABLED')}
				{includePart portal/parts/item-extension}
			{/if}
			{* ITEM EXTENSION *}

			{* SPECIAL OFFERS SECTION *}
			{if (defined('AIT_SPECIAL_OFFERS_ENABLED'))}
				{includePart parts/single-item-special-offers}
			{/if}
			{* SPECIAL OFFERS SECTION *}

			{* FOOD MENU SECTION *}
			{if defined('AIT_FOOD_MENU_ENABLED')}
				{var $menuTypes = $options->theme->foodMenu->menuTypes}
				{if !empty($menuTypes)}
					{var $featuredMenuType =  array()}
					{var $featuredMenuType['id'] = $options->theme->foodMenu->featuredMenuType}
					{foreach $options->theme->foodMenu->menuTypes as $menuType}
						{if $menuType->id == $featuredMenuType['id']}
							{var $featuredMenuType['title'] = $menuType->title}
						{/if}
					{/foreach}
					{includePart portal/parts/single-item-special-food-menu specialMenuType => $featuredMenuType, postId => $post->id}
					{includePart portal/parts/single-item-food-menu specialMenuType => $featuredMenuType, postId => $post->id}
				{/if}
			{/if}
			{* FOOD MENU SECTION *}

			{* GALLERY SECTION *}
			{includePart portal/parts/single-item-gallery}
			{* GALLERY SECTION *}

			{* FEATURES SECTION *}
			{includePart portal/parts/single-item-features}
			{* FEATURES SECTION *}

			{* REVIEWS SECTION *}
			{if defined('AIT_REVIEWS_ENABLED')}
			{includePart portal/parts/single-item-reviews}
			{/if}
			{* REVIEWS SECTION *}

			{* CLAIM LISTING SECTION *}
			{if defined('AIT_CLAIM_LISTING_ENABLED')}
				{includePart portal/parts/claim-listing}
			{/if}
			{* CLAIM LISTING SECTION *}

		{*RICH SNIPPET WRAP*}
		</div>
		{*RICH SNIPPET WRAP*}

	{/loop}
