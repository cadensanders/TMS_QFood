{getHeader}

{var $layoutType = $options->layout->custom->layout}
{var $taxHeader = 'none'}
{var $headerType = " header-type-".$options->layout->general->headerType}
{var $toggleMap = false}
{var $isAdvanced = $wp->isSearch && empty($_REQUEST['a']) ? ' basic-search' : ''}

{if $wp->isTax(items) or $wp->isTax(locations)}
	{var $meta = (object) get_option("{$taxonomyTerm->taxonomy}_category_{$taxonomyTerm->id}")}
	{var $taxHeader = isset($meta->header_type) ? $meta->header_type : 'map'}
	{var $headerType = " header-type-".$taxHeader}
{elseif $wp->isSingular(item)}
	{var $meta = $post->meta('item-data')}
	{var $headerType = " header-type-".$meta->headerType}
{/if}

{* PAGE BUILDER OPTION IS DOMINANT*}
{var $headerType = $options->layout->general->headerType == 'none' ? " header-type-none" : $headerType}
{var $headerType = ($headerType == " header-type-map" && empty($elements->unsortable[header-map]->display)) ? " header-type-none" : $headerType}

{* ENABLE TOGGLE FOR RESPONSIVE AREAS *}

{if $wp->isTax(items) or $wp->isTax(locations) or $wp->isSingular(item) or ($wp->isSearch && isset($_REQUEST['a']) && $_REQUEST['a'] != "")}
	{if ($headerType == ' header-type-map' && $elements->unsortable[header-map]->display) || $elements->unsortable[search-form]->display}
		{var $toggleMap = true}
	{/if}
{/if}

<div class="page-content {!$layoutType}{!$headerType}{!$isAdvanced}">

	{if $elements->unsortable[taxonomy-menu]->display}
		{includeElement $elements->unsortable[taxonomy-menu]}
	{/if}

	{if $toggleMap}
	<div class="ait-toggle-area-group toggle-map">
		{if $headerType == ' header-type-map' && $elements->unsortable[header-map]->display}
		<a href="#" id="toggle-map" class="ait-toggle-area-btn" data-toggle=".right-wrap"><i class="fa fa-map-o"></i> {__ 'Toggle Map'}</a>
		{/if}
		{if $elements->unsortable[search-form]->display}
		<a href="#" id="toggle-search" class="ait-toggle-area-btn" data-toggle='[".search-form"{if $headerType == " header-type-image"}, ".right-wrap"{/if}]'><i class="fa fa-search"></i> {__ 'Toggle Search'}</a>
		{/if}
	</div>
	{/if}

	{if $headerType != ' header-type-none' or $taxHeader != 'none'}
	<div class="right-wrap{if $toggleMap} ait-toggle-area {if $headerType == ' header-type-image'}preserve-height active{/if}{/if}">
		{if $wp->isSingular(item) or $wp->isTax(items) or $wp->isTax(locations)}
			{* USE THE SETTINGS DEFINED IN THE ITEM OR TAXONOMY *}
			{if $wp->isSingular(item)}
				{* ITEM DETAIL *}

				{var $meta = $post->meta('item-data')} {* $post is global entity available on singular pages *}

				{if $meta->headerType === 'map'}

					{if $elements->unsortable[header-map]->display}
					<div class="header-wrap">
						{includeElement $elements->unsortable[header-map]}
					</div>
					{/if}

				{elseif $meta->headerType === 'image'}

					{if isset($meta->headerImage) and !empty($meta->headerImage)}
					<div class="header-wrap has-image" style="background-image: url('{!$meta->headerImage}')"></div>
					{else}
					<div class="header-wrap has-image" style="background-image: url('{!$options->theme->item->noHeader}')"></div>
					{/if}

				{/if}

			{elseif $wp->isTax(items) or $wp->isTax(locations)}
				{* ITEM CATEGORY && LOCATION *}
				{if $taxHeader === 'map'}
					{if $elements->unsortable[header-map]->display}
					<div class="header-wrap">
						{includeElement $elements->unsortable[header-map]}
					</div>
					{/if}
				{elseif $taxHeader === 'image'}
					{if isset($meta->header_image) && $meta->header_image != ""}
						<div class="header-wrap has-image" style="background-image: url('{!$meta->header_image}')"></div>
					{else}
						{* Default Header Image *}
						{if $wp->isTax(items)}
						<div class="header-wrap has-image" style="height: {$options->theme->items->headerImageHeight}px; overflow: hidden; background-image: url('{$options->theme->items->categoryDefaultImage}')"></div>
						{else}
						<div class="header-wrap has-image" style="height: {$options->theme->items->headerImageHeight}px; overflow: hidden; background-image: url('{$options->theme->items->locationDefaultImage}')"></div>
						{/if}
					{/if}
				{/if}
			{/if}

		{else}

			{* USE THE DEFAULT SETTINGS *}
			{if $options->layout->general->headerType == 'map'}

				{if $elements->unsortable[header-map]->display}
				<div class="header-wrap">
					{includeElement $elements->unsortable[header-map]}
				</div>
				{/if}

			{elseif $options->layout->general->headerType == 'revslider'}

				{if $elements->unsortable[revolution-slider]->display}
				<div class="header-wrap">
					{includeElement $elements->unsortable[revolution-slider]}
				</div>
				{/if}

			{elseif $options->layout->general->headerType == 'image'}

				{if $wp->isSingular(post) and $post->hasImage}
					<div class="header-wrap has-image" style="background-image: url('{!$post->imageUrl}')"></div>
				{else}
					{if $options->layout->general->headerImage}
					<div class="header-wrap has-image" style="background-image: url('{!$options->layout->general->headerImage}')"></div>
					{/if}
				{/if}

			{/if}
		{/if}
	</div>
	{/if}

	<div class="left-wrap">

		<div id="main" class="elements">

			{if $elements->unsortable[search-form]->display}
				{includeElement $elements->unsortable[search-form]}
			{/if}

			{if $elements->unsortable[page-title]->display}
				{includeElement $elements->unsortable[page-title]}
			{/if}

			{if $layoutType == 'full'}

				<div class="main-sections">
				{foreach $elements->sortable as $element}

					{if $element->id == sidebars-boundary-start}

					<div class="elements-with-sidebar">
						<div class="elements-sidebar-wrap">
							<div class="right-bck"></div>
							{if $wp->hasSidebar(left)}
								{getSidebar left}
							{/if}
							<div class="elements-area">

					{elseif $element->id == sidebars-boundary-end}

							</div><!-- .elements-area -->
							{if $wp->hasSidebar(right)}
								{getSidebar}
							{/if}
						</div><!-- .elements-sidebar-wrap -->
					</div><!-- .elements-with-sidebar -->

					{else}
						{? global $post}
						{if $element->id == 'comments' && $post == null}
							<!-- COMMENTS DISABLED - IS NOT SINGLE PAGE -->
						{elseif $element->id == 'comments' && !comments_open($post->ID) && get_comments_number($post->ID) == 0}
							<!-- COMMENTS DISABLED -->
						{else}
							<section n:if="$element->display" id="{$element->htmlId}-main" class="{$element->htmlClasses}">

								<div class="elm-wrapper {$element->htmlClass}-wrapper">

									{includeElement $element}

								</div><!-- .elm-wrapper -->

							</section>
						{/if}
					{/if}
				{/foreach}
				</div><!-- .main-sections -->

			{else}

				<div class="main-sections">
					{foreach $elements->sortable as $element}

						{if $element->id == sidebars-boundary-start or $element->id == sidebars-boundary-end}
						{else}
							{? global $post}
							{if $element->id == 'comments' && $post == null}
								<!-- COMMENTS DISABLED - IS NOT SINGLE PAGE -->
							{elseif $element->id == 'comments' && !comments_open($post->ID) && get_comments_number($post->ID) == 0}
								<!-- COMMENTS DISABLED -->
							{else}
								<section n:if="$element->display" id="{$element->htmlId}-main" class="{$element->htmlClasses}">

									<div class="elm-wrapper {$element->htmlClass}-wrapper">

										{includeElement $element}

									</div><!-- .elm-wrapper -->

								</section>
							{/if}
						{/if}

					{/foreach}

					{if $wp->hasSidebar(left) or $wp->hasSidebar(right)}
					<div class="sidebars-wrap">
						{if $wp->hasSidebar(left)}
							{getSidebar left}
						{/if}

						{if $wp->hasSidebar(right)}
							{getSidebar}
						{/if}
					</div>
					{/if}

				</div>

			{/if}

		</div><!-- #main .elements -->

		{getFooter}

	</div><!-- .left-wrap -->

</body>
</html>
