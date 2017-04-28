<span class="author vcard">
	<span class="auth-links">
		{*var $postAuthor = <a class="url fn n" href="{$post->author->postsUrl}" title="{__ 'View all posts by %s'|printf: $post->author}" rel="author">{$post->author}</a>*}
		{capture $postAuthor}
			<a class="url fn n" href="{$post->author->postsUrl}" title="{__ 'View all posts by %s'|printf: $post->author}" rel="author">{$post->author}</a>
		{/capture}

		{if isset($desc) and $desc}
			{!__ 'Posted by: %s'|printf: $postAuthor}
		{else}
			{!$postAuthor}
		{/if}
	</span>
</span>
