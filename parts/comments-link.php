{if $post->hasCommentsOpen}
	<div class="comments-link">
		<a href="{$post->commentsUrl}" title="{__ 'Comments on %s'|printf: $post->title}">
			{if $post->commentsNumber > 1}
				<span class="comments-count" title="{_n '%s Comment', '%s Comments'|printf: $post->commentsNumber}">
					{$post->commentsNumber}
				</span>
			{elseif $post->commentsNumber == 0}
				<span class="comments-count" title="{__ 'Leave a comment'}">
					0
				</span>
			{else}
				<span class="comments-count" title="{__ '1 Comment'}">
					1
				</span>
			{/if}
		</a>
	</div><!-- .comments-link -->
{/if}