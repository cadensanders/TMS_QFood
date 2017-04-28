{* VARIABLES *}
{var $concreteTaxonomy = isset($taxonomy) && $taxonomy != "" ? $taxonomy : ''}
{* VARIABLES *}


	{if !$wp->isSingular}

		{if $wp->isSearch}

			{var $isAdvanced = !empty($_REQUEST['a'])}

			{if $isAdvanced}

				{includePart "portal/parts/item-container"}

			{else}
				{*** SEARCH RESULTS ONLY ***}

				{var $nothumbnail = !$post->hasImage ? ' nothumbnail' : ''}

				<article {!$post->htmlId} {!$post->htmlClass('hentry'.$nothumbnail)}>
					<header class="entry-header {if !$post->hasImage}nothumbnail{/if}">

						<div class="entry-thumbnail">

							{if $post->hasImage}
								<div class="entry-thumbnail-wrap entry-content" style="background-image: url('{imageUrl $post->imageUrl, width => 1000, height => 580, crop => 1}');">
									<a href="{$post->permalink}" class="thumb-link"></a>
								</div>
							{/if}

							{capture $editLinkLabel}<span class="edit-link">{!__ 'Edit'}</span>{/capture}
							{!$post->editLink($editLinkLabel)}

							<div class="entry-meta-info">

								{var $dateIcon = $post->date}
								{var $dateLinks = 'no'}
								{var $dateShort = 'no'}

								{includePart parts/entry-date-format, dateIcon => $dateIcon, dateLinks => $dateLinks, dateShort => $dateShort}

								{includePart parts/comments-link}

							</div><!-- /.entry-meta-info -->

						</div>

					</header><!-- /.entry-header -->

					<div class="entry-wrapper">

						<div class="entry-title">
							<div class="entry-title-wrap">
								<h2><a href="{$post->permalink}">{!$post->title}</a></h2>
							</div><!-- /.entry-title-wrap -->
						</div><!-- /.entry-title -->


						<div class="entry-content loop">
							{if $post->hasContent}
								{!$post->excerpt|trimWords:30}
							{else}
								{!$post->content|trimWords:30}
							{/if}
						</div><!-- .entry-content -->

						<footer class="entry-footer">
							<div class="entry-data">
								{if $post->tagList}
								<span class="tags">
									<span class="tags-links">{!$post->tagList(" ")}</span>
								</span>
								{/if}
							</div><!-- .entry-data -->
						</footer><!-- .entry-footer -->

					</div><!-- .entry-wrapper -->
				</article>
			{/if}

		{else}

			{*** STANDARD LOOP ***}
			{var $nothumbnail = ""}
			{if !$post->hasImage}
				{var $nothumbnail = ' nothumbnail'}
			{/if}

			<article {!$post->htmlId} {!$post->htmlClass('hentry'.$nothumbnail)}>
				<header class="entry-header {if !$post->hasImage}nothumbnail{/if}">

					<div class="entry-thumbnail">
						{if $post->hasImage}
							<div class="entry-thumbnail-wrap entry-content" style="background-image: url('{imageUrl $post->imageUrl, width => 1000, height => 580, crop => 1}');">
								<a href="{$post->permalink}" class="thumb-link"></a>
							</div>
						{/if}

						{capture $editLinkLabel}<span class="edit-link">{!__ 'Edit'}</span>{/capture}
						{!$post->editLink($editLinkLabel)}

                        <div class="entry-meta-info">

                            {var $dateIcon = $post->date}
                            {var $dateLinks = 'no'}
                            {var $dateShort = 'no'}

							{includePart parts/entry-date-format, dateIcon => $dateIcon, dateLinks => $dateLinks, dateShort => $dateShort}

                            {includePart parts/comments-link}

                        </div><!-- /.entry-meta-info -->

					</div>

				</header><!-- /.entry-header -->

                <div class="entry-wrapper">

					<div class="entry-title">

						<div class="entry-title-wrap">

							<h2><a href="{$post->permalink}">{!$post->title}</a></h2>

						</div><!-- /.entry-title-wrap -->

					</div><!-- /.entry-title -->


					<div class="entry-content loop">
						{if $post->hasContent}
							{!$post->excerpt|trimWords:30}
						{else}
							{!$post->content|trimWords:30}
						{/if}


					</div><!-- .entry-content -->

					<footer class="entry-footer">

						<div class="entry-data">

							{*if $post->type == post}
								{includePart parts/entry-author}
							{/if*}

							{if $post->tagList}
							<span class="tags">
								<span class="tags-links">{!$post->tagList(" ")}</span>
							</span>
							{/if}


							{*if $post->isInAnyCategory}
								{includePart parts/entry-categories}
							{/if*}

						</div><!-- .entry-data -->

                    </footer><!-- .entry-footer -->

                </div><!-- .entry-wrapper -->
			</article>
		{/if}

	{else}

		{*** POST DETAIL ***}

		<article {!$post->htmlId} class="content-block hentry">

			<div class="entry-title hidden-tag">
				<h2>{!$post->title}</h2>
			</div>

			<div class="entry-thumbnail">
				{if $options->layout->general->headerType != 'image' and $post->hasImage}
					<div class="entry-thumbnail-wrap">
					 <a href="{$post->imageUrl}" class="thumb-link">
					  <span class="entry-thumbnail-icon">
						<img src="{imageUrl $post->imageUrl, width => 1000, height => 500, crop => 1}" alt="{!$post->title}">
					  </span>
					 </a>
					</div>
				{/if}
			</div>

			<div class="entry-content">
				{!$post->content}
				{!$post->linkPages}
			</div><!-- .entry-content -->

			<footer class="entry-footer">

				{if $post->categoryList}
					<div class="categories">
						{!$post->categoryList('', '', category)}
					</div>
				{/if}

				{if $post->tagList}
					<div class="tags">
						<div class="tags-links">{!$post->tagList('')}</div>
					</div>
				{/if}

			</footer><!-- .entry-footer -->

			{includePart parts/author-bio}

		</article>

	{/if}
