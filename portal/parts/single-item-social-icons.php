{var $meta = $post->meta('item-data')}

{var $target = $meta->socialIconsOpenInNewWindow ? 'target="_blank"' : ""}

{if $meta->displaySocialIcons}
<div class="social-icons-container">
	<div class="content">
		{if count($meta->socialIcons) > 0}
			<ul><!--
			{foreach $meta->socialIcons as $icon}
			--><li>
					<a href="{!$icon['link']}" {!$target}>
						{if isset($icon['icon']) && $icon['icon'] != "" }
						{var $iconColor = $icon['iconColor'] != "" ? $icon['iconColor'] : getDefaultSocialIconColor($icon['icon'])}
						<i class="fa {$icon['icon']}" style="color: {!$iconColor}"></i>
						{else}
						<img src="{$icon['image']}" alt="icon">
						{/if}
					</a>
				</li><!--
			{/foreach}
			--></ul>
		{/if}
	</div>
</div>
{/if}
