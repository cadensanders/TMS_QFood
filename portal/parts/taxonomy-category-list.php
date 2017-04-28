{var $currentCategory = get_term_by( 'slug', get_query_var($taxonomy), $taxonomy)}
{var $parentCategory = $currentCategory != false ? $currentCategory->term_id : 0}
{var $categories = $wp->categories(array('taxonomy' => $taxonomy, 'parent' => $parentCategory))}
{var $class = isset($class) ? $class : ''}
{var $customScroll = isset($customScroll) ? $customScroll : false}

{if isset($categories) && count($categories) > 0}
	{var $columns = $options->theme->items->categoryColumns}
	<div class="categories-container {if $customScroll}optiscroll{/if}">
		<div class="content {if $customScroll}optiscroll-content{/if} {$class}">
			<ul n:class='"column-{$columns}",'><!--
			{foreach $categories as $category}
				{var $title = $category->title}
				{var $count = aitGetTermPostCount($taxonomy, $category->id)}
				{var $desc = $category->description}
				{var $link = get_term_link( $category->id, $category->taxonomy )}
				{var $icons = get_option($category->taxonomy . "_category_" . $category->id)}
				{var $icon = ""}

				{if isset($icons['icon']) && $icons['icon'] != ""}
					{var $icon = $icons['icon']}
				{else}
					{if $category->parentId != 0}
						{var $parent = get_term($category->parentId, $taxonomy)}
						{var $icons = get_option($parent->taxonomy . "_category_" . $parent->term_id)}
						{if isset($icons['icon']) && $icons['icon'] != ""}
							{var $icon = $icons['icon']}
						{else}
							{if $taxonomy == "ait-items"}
							{var $icon = $options->theme->items->categoryDefaultIcon}
							{else}
							{var $icon = $options->theme->items->locationDefaultIcon}
							{/if}
						{/if}
					{else}
						{if $taxonomy == "ait-items"}
						{var $icon = $options->theme->items->categoryDefaultIcon}
						{else}
						{var $icon = $options->theme->items->locationDefaultIcon}
						{/if}
					{/if}
				{/if}

				--><li n:class="$title ? 'has-title', has-icon, $desc ? 'has-desc'">
					<a href="{$link}">
						<div class="data">
							<img src="{$icon}" alt="icon">
							<div class="title">{!$title}</div>
							<div class="count">{!$count}</div>
							{if $desc}
							<div class="desc"><p class="{if strpos($class,'tax-detail') !== false}txtrows-1{/if}">{!$desc|trimWords: 10|striptags}</p></div>
							{/if}
						</div>
					</a>
					{*
					{if $desc}
					<div class="cat-desc">
						{!$desc|trimWords: 10}
					</div>
					{/if}
					*}
				</li><!--
			{/foreach}
			--></ul>
		</div>
	</div>
{/if}
