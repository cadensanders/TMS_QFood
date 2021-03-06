{var $resources = get_posts(array('post_type' => 'ait-item', 'category' => 0, 'posts_per_page' => -1))}

{if $options->theme->header->displayHeaderResources}
<div class="header-resources">

	<span class="resources-data">
		<span class="resources-count">{count($resources)}</span>
		<span class="resources-text">{__ 'Resources'}</span>
	</span>

	{if function_exists('pll_get_post')}
		{var $hrlink = get_permalink( pll_get_post( $options->theme->header->headerResourcesButtonLink ) )}
	{else}
		{var $hrlink = get_permalink( $options->theme->header->headerResourcesButtonLink )}
	{/if}

	{var $link = is_user_logged_in() ? admin_url('post-new.php?post_type=ait-item') : $hrlink}
	<a href="{!$link}" class="resources-button ait-sc-button">{__ 'Submit'}</a>

</div>
{/if}
