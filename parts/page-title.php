{* ********************************************************* *}
{* COMMON DATA                                               *}
{* ********************************************************* *}

	{capture $editLinkLabel}<span class="edit-link">{!__ 'Edit'}</span>{/capture}

	{var $titleClass = ''}
	{var $titleName = ''}
	{var $editButton = ''}
	{var $titleImage = ''}
	{var $dateIcon = ''}
	{var $dateLinks = ''}
	{var $dateShort = ''}
	{var $dateInterval = ''}
	{var $titleAuthor = ''}
	{var $titleCategory = ''}
	{var $titleComments = ''}
	{var $titleSubDesc = ''}
	{var $titleDesc = $el->option(description)}
	{var $showPager = ''}

	{* Page Title Background *}

	{var $hasBg = $el->option(background)}
	{var $hasBg = $hasBg[image]}
	{var $hasBgClass = ''}
	{var $style = ''}

	{if $hasBg != ''}
		{var $hasBgClass = 'has-bg'}

	{elseif $wp->isSingular(item)}
		{loop as $post}
			{var $bgImage = $post->imageUrl != '' ? $post->imageUrl : $options->theme->item->noFeatured}
			{var $meta = $post->meta('item-data')}
		{/loop}
		{if $options->layout->custom->layout == 'half' and $meta->headerType != 'image'}
			{var $hasBgClass = 'has-bg'}
			{var $style = 'style="background-image: url('. $bgImage .');"'}
		{/if}

	{elseif $wp->isTax(items) or $wp->isTax(locations)}
		{var $meta = (object) get_option("{$taxonomyTerm->taxonomy}_category_{$taxonomyTerm->id}")}
		{var $headerType = isset($meta->header_type) ? $meta->header_type : 'map'}
		{if isset($meta->header_image) and $meta->header_image != '' and $headerType != 'image'}
			{var $hasBgClass = 'has-bg'}
			{var $style = 'style="background-image: url('. $meta->header_image .');"'}
		{/if}
	{/if}

	{* Page Title Background *}


{* ********************************************************* *}
{* for 404, SEARCH and WOOCOMMERCE                           *}
{* ********************************************************* *}

{if $wp->is404 or $wp->isSearch or $wp->isWoocommerce()}

	{* CLASS ********** *} {if $wp->is404}				{var $titleClass = "simple-title"} {/if}
	{* CLASS ********** *} {if $wp->isSearch}			{var $titleClass = "simple-title"} {/if}
	{* CLASS ********** *} {if $wp->isWoocommerce()}	{var $titleClass = "simple-title"} {/if}

	{* TITLE ********** *} {if $wp->is404}				{capture $titleName}{__ "This is somewhat embarrassing, isn't it?"}{/capture}			{/if}
	{* TITLE ********** *} {if $wp->isSearch}
								{if isset($_REQUEST['a']) && $_REQUEST['a'] != ""}
														{var $sString = array()}
														{if isset($_REQUEST['s']) && $_REQUEST['s'] != ""}{? array_push($sString, htmlspecialchars($_REQUEST['s']))}{/if}
														{if isset($_REQUEST['category']) && $_REQUEST['category'] != ""}
															{var $dCategory = get_term($_REQUEST['category'], 'ait-items')}
															{if isset($dCategory)}{? array_push($sString, $dCategory->name)}{/if}
														{/if}
														{if isset($_REQUEST['location']) && $_REQUEST['location'] != ""}
															{var $dLocation = get_term($_REQUEST['location'], 'ait-locations')}
															{if isset($dLocation)}{? array_push($sString, $dLocation->name)}{/if}
														{/if}

														{capture $titleName}
															{capture $searchTitle}<span class="title-data">{implode(", ", $sString)}</span>{/capture}
															{if count($sString) > 0}
															{!__ 'Search Results for: %s'|printf: $searchTitle}
															{else}
															{!__ 'Search Results: %s'|printf: $searchTitle}
															{/if}
														{/capture}
								{else}
														{capture $titleName}
															{capture $searchTitle}<span class="title-data">{$wp->searchQuery}</span>{/capture}
															{!__ 'Search Results for: %s'|printf: $searchTitle}
														{/capture}
								{/if}
							{/if}

	{* TITLE ********** *} {if $wp->isWoocommerce()}	{capture $titleName}{? woocommerce_page_title()}{/capture}								{/if}

{* ********************************************************* *}
{* for PAGES, POST DETAIL, IMAGE DETAIL and PORTFOLIO DETAIL *}
{* for LOOP pages only                                       *}
{* ********************************************************* *}

{elseif $wp->isPage or $wp->isSingular(post) or $wp->isSingular(portfolio-item) or $wp->isSingular(event) or $wp->isSingular(job-offer) or $wp->isSingular(item) or $wp->isAttachment}
{loop as $post}

	{* CLASS ********** *} {if $wp->isPage} 					{var $titleClass = "standard-title"} 				{/if}
	{* CLASS ********** *} {if $wp->isSingular(post)} 			{var $titleClass = "post-title"} 					{/if}
	{* CLASS ********** *} {if $wp->isSingular(portfolio-item)} {var $titleClass = "post-title portfolio-title"} 	{/if}
	{* CLASS ********** *} {if $wp->isSingular(event)} 			{var $titleClass = "post-title event-title"} 		{/if}
	{* CLASS ********** *} {if $wp->isSingular(job-offer)} 		{var $titleClass = "post-title job-offer-title"}	{/if}
	{* CLASS ********** *} {if $wp->isAttachment}				{var $titleClass = "post-title attach-title"}		{/if}

	{* META DATA ****** *} {if $wp->isSingular(event)}			{var $meta = $post->meta(event-data)}
						   {elseif $wp->isSingular(job-offer)}	{var $meta = $post->meta(offer-data)}
						   {/if}

	{* TITLE ********** *} {var $titleName = $post->title}
	{* IMAGE ********** *} {var $titleImage = $post->imageUrl}
						   {if $wp->isAttachment or $wp->isSingular(portfolio-item) or $wp->isSingular(job-offer) or $wp->isSingular(post) or $wp->isSingular(event) or $wp->isSingular(item)} {var $titleImage = ''} {/if}
	{* EDIT *********** *} {capture $editButton}{!$post->editLink($editLinkLabel)}{/capture}

	{* DATE ICON ****** *} {if $wp->isSingular(portfolio-item)} {var $dateIcon = FALSE} 			{var $dateLinks = 'no'} 	{var $dateShort = 'no'} {/if}
	{* DATE ICON ****** *} {if $wp->isSingular(event)} 			{var $dateIcon = FALSE} 			{var $dateLinks = 'no'} 	{var $dateShort = 'no'} {/if}
	{* DATE ICON ****** *} {if $wp->isSingular(job-offer)} 		{var $dateIcon = FALSE} 			{var $dateLinks = 'no'} 	{var $dateShort = 'no'} {/if}
	{* DATE ICON ****** *} {if $wp->isAttachment} 				{var $dateIcon = $post->date}  		{var $dateLinks = 'no'}		{var $dateShort = 'no'} {/if}

	{* DATE INTERVAL ** *} {if $wp->isSingular(event)}			{capture $intLabel}{__ 'Duration:'}{/capture}
																{var $intFrom = $meta->dateFrom}
																{var $intTo = $meta->dateTo}
																{if $intTo}{var $dateInterval = 'yes'}{/if}
						   {/if}
	{* DATE INTERVAL ** *} {if $wp->isSingular(job-offer)}		{capture $intLabel}{__ 'Validity:'}{/capture}
																{var $intFrom = $meta->validFrom}
																{var $intTo = $meta->validTo}
																{var $dateInterval = 'yes'}
						   {/if}

	{* AUTHOR ********* *} {if $wp->isAttachment} 				{var $titleAuthor = 'yes'} {/if}

	{* CATEGORY ******* *} {if $post->categoryList}				{var $titleCategory = 'no'} {/if}


	{* ITEM ICON ****** *} {if $wp->isSingular(item)}

								{var taxonomies = $post->taxonomies}
								{var $terms = get_the_terms($post->id, reset($taxonomies))}
								{if $terms}
									{var $category = reset($terms)}
									{var $icons = get_option($category->taxonomy . "_category_" . $category->term_id)}
									{if isset($icons['icon']) && $icons['icon'] != ""}
										{capture $categoryIcon}
											{$icons['icon']}
										{/capture}
									{else}
										{if $category->parent != 0}
											{? global $wp_query}
											{var $category = get_term($category->parent, $category->taxonomy)}
											{var $icons = get_option($category->taxonomy . "_category_" . $category->term_id)}
											{if isset($icons['icon']) && $icons['icon'] != ""}
												{capture $categoryIcon}
													{$icons['icon']}
												{/capture}
											{else}
												{capture $categoryIcon}
													{if $category->taxonomy == "ait-items"}
													{$options->theme->items->categoryDefaultIcon}
													{else}
													{$options->theme->items->locationDefaultIcon}
													{/if}
												{/capture}
											{/if}
										{else}
											{capture $categoryIcon}
												{if $category->taxonomy == "ait-items"}
												{$options->theme->items->categoryDefaultIcon}
												{else}
												{$options->theme->items->locationDefaultIcon}
												{/if}
											{/capture}
										{/if}
									{/if}
								{/if}
							{/if}

{/loop}

{* ********************************************************* *}
{* for BLOG PAGE ONLY                                        *}
{* ********************************************************* *}

{elseif $wp->isBlog and $blog}

	{* CLASS ********** *} {var $titleClass = "blog-title"}
	{* TITLE ********** *} {var $titleName = $blog->title}
	{* IMAGE ********** *} {var $titleImage = $blog->imageUrl}
	{* EDIT *********** *} {capture $editButton}{!$blog->editLink($editLinkLabel)}{/capture}

{* ********************************************************* *}
{* for CATEGORY, ARCHIVE, TAG and AUTHOR                     *}
{* ********************************************************* *}

{elseif $wp->isCategory or $wp->isArchive or $wp->isTag or $wp->isAuthor or $wp->isTax(portfolios) or $wp->isTax(items) or $wp->isTax(locations)}

	{* CLASS ********** *} {var $titleClass = "archive-title"}

	{* TITLE ********** *} {if $wp->isCategory}					{capture $titleName}
																	{capture $categoryTitle}<span class="title-data">{!$category->title}</span>{/capture}
																	{!__ 'Category Archives: %s'|printf: $categoryTitle}
																{/capture}

	{* TITLE ********** *} {elseif $wp->isTax(items) or $wp->isTax(locations)}
								{var $category = get_queried_object()}
								{capture $titleName}
									{capture $categoryTitle}<span class="title-data">{!$category->name}</span>{/capture}
									{!__ '%s'|printf: $categoryTitle}
								{/capture}

	{* TITLE ********** *} {elseif $wp->isTag}					{capture $titleName}
																	{capture $tagTitle}<span class="title-data">{$tag->title}</span>{/capture}
																	{!__ 'Tag Archives: %s'|printf: $tagTitle}
																{/capture}
	{* TITLE ********** *} {elseif $wp->isPostTypeArchive}		{capture $titleName}
																	{capture $archiveTitle}<span class="title-data">{$archive->title}</span>{/capture}
																	{!__ 'Archives: %s'|printf: $archiveTitle}
																{/capture}
	{* TITLE ********** *} {elseif $wp->isTax}					{capture $titleName}
																	{capture $taxonomyTitle}<span class="title-data">{$taxonomyTerm->title}</span>{/capture}
																	{!__ 'Category Archives: %s'|printf: $taxonomyTitle}
																{/capture}
	{* TITLE ********** *} {elseif $wp->isAuthor}				{capture $titleName}
																	{capture $authorTitle}<span class="title-data">{$author}</span>{/capture}
																	{!__ 'All posts by %s'|printf: $authorTitle}
																{/capture}
	{* TITLE ********** *} {elseif $wp->isArchive}
								{if $archive->isDay}			{capture $titleName}
																	{capture $dayTitle}<span class="title-data">{$archive->date}</span>{/capture}
																	{!__ 'Daily Archives: %s'|printf: $dayTitle}
																{/capture}
								{elseif $archive->isMonth}		{capture $titleName}
																	{capture $monthFormat}{_x 'F Y', 'monthly archives date format'}{/capture}
																	{capture $monthTitle}<span class="title-data">{$archive->date($monthFormat)}</span>{/capture}
																	{!__ 'Monthly Archives: %s'|printf: $monthTitle}
																{/capture}
								{elseif $archive->isYear}		{capture $titleName}
																	{capture $yearFormat}{_x 'Y',  'yearly archives date format'}{/capture}
																	{capture $yearTitle}<span class="title-data">{$archive->date($yearFormat)}</span>{/capture}
																	{!__ 'Yearly Archives: %s'|printf: $yearTitle}
																{/capture}
								{else}							{capture $titleName}{!__ 'Archives:'}{/capture}
								{/if}
						   {/if}

	{* CATEGORY ICON ** *} {if ($wp->isTax(items) or $wp->isTax(locations)) and isset($category)}

								{var $icons = get_option($category->taxonomy . "_category_" . $category->term_id)}
								{if isset($icons['icon']) && $icons['icon'] != ""}
									{capture $categoryIcon}
										{$icons['icon']}
									{/capture}
								{else}
									{if $category->parent != 0}
										{? global $wp_query}
										{var $taxonomy = $wp_query->tax_query->queries[0]['taxonomy']}
										{var $category = get_term($category->parent, $taxonomy)}
										{var $icons = get_option($category->taxonomy . "_category_" . $category->term_id)}
										{if isset($icons['icon']) && $icons['icon'] != ""}
											{capture $categoryIcon}
												{$icons['icon']}
											{/capture}
										{else}
											{capture $categoryIcon}
												{if $category->taxonomy == "ait-items"}
												{$options->theme->items->categoryDefaultIcon}
												{else}
												{$options->theme->items->locationDefaultIcon}
												{/if}
											{/capture}
										{/if}
									{else}
										{capture $categoryIcon}
											{if $category->taxonomy == "ait-items"}
											{$options->theme->items->categoryDefaultIcon}
											{else}
											{$options->theme->items->locationDefaultIcon}
											{/if}
										{/capture}
									{/if}
								{/if}
							{/if}

	{* SUBDESC ******** *} {if $wp->isCategory}					{var $titleSubDesc = $category->description} 	{/if}
	{* SUBDESC ******** *} {if $wp->isTag}						{var $titleSubDesc = $tag->description} 		{/if}

{/if}


{* ********************* *}
{* RESULTS               *}
{* ********************* *}

<div style="display: none;">
{$titleClass}
{!$titleName}
{!$editButton}
{$titleImage}
{$dateIcon}
{$dateLinks}
{$dateShort}

{if $dateInterval == 'yes'}{$intLabel} {$intFrom} - {$intTo}{/if}
{if $titleAuthor == 'yes'}{includePart parts/entry-author}{/if}
{if $titleCategory == 'yes'}{includePart parts/entry-categories}{/if}
{if $titleComments == 'yes'}{includePart parts/comments-link}{/if}
{!$titleSubDesc}
{!$titleDesc}
</div>

<div n:class="'page-title', $options->layout->general->showBreadcrumbs ? 'has-breadcrumbs', (isset($categoryIcon) and !$wp->isSingular(item)) ? 'has-icon', $hasBgClass" {$style}>
	<div class="grid-main">
		{if defined('AIT_REVIEWS_ENABLED') && isset($post)}
		<header class="entry-header {AitItemReviews::itemHasReviews($post->id)}">
		{else}
		<header class="entry-header">
		{/if}
			<div class="entry-header-left">

				<div class="entry-title {$titleClass}">
					<div class="entry-title-wrap{if $wp->isSingular(post)} hentry{/if}">

						{if isset($categoryIcon) && !$wp->isSingular(item)}
							<img src="{$categoryIcon}" alt="{!titleName}">
						{/if}

						{if $wp->isSingular(post)}
						<div class="entry-meta-info">

							{var $dateIcon = $post->date}
							{var $dateLinks = 'no'}
							{var $dateShort = 'no'}

							{includePart parts/entry-date-format, dateIcon => $dateIcon, dateLinks => $dateLinks, dateShort => $dateShort}

							{includePart parts/comments-link}

						</div><!-- /.entry-meta-info -->
						{/if}

						<h1>{!$titleName}</h1>

						{if $wp->isSingular(item) and defined('AIT_FOOD_MENU_ENABLED')}
							{var $priceRating = get_post_meta($post->id, '_ait-item_food-options_price', true)}
							{includePart parts/food-menu-price-rating, class => "header", rating => $priceRating}

						{/if}

						{if $wp->isSingular(post)}{includePart parts/entry-author, desc => true}{/if}

						{if $titleDesc}
							<div class="page-description">{!$titleDesc}</div>
						{/if}

						{includePart parts/breadcrumbs}

						{* Item Location *}
						{if $wp->isSingular(item)}
							{var $meta = $post->meta('item-data')}
							<div class="item-location"><p><i class="fa fa-map-marker"></i> {$meta->map['address']}</p></div>
						{/if}

					</div>
				</div>

			</div>

			{if $wp->isSingular(item)}
			<div class="entry-header-right">
				{if defined('AIT_REVIEWS_ENABLED')}
					{includePart portal/parts/single-item-reviews-stars, showCount => true}
				{/if}
			</div>
			{/if}

		</header><!-- /.entry-header -->

	</div>
</div>



