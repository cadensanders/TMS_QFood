{var $shareUrl 			= $post->permalink}
{var $shareTitle 		= $post->title}
{var $shareImage 		= $post->imageUrl != '' ? $post->imageUrl : $options->theme->item->noFeatured}
{var $shareDescription	= substr(strip_tags($post->excerpt), 0, 100)}

{var $twitterReferral   = get_bloginfo( 'name' )}

{* PREPARE FOR SHARE
{var $strFind = array("&")}
{var $strReplace = array("and")}
{var $shareTitle = urlencode(str_replace($strFind, $strReplace, $shareTitle))}
PREPARE FOR SHARE *}

<div class="social-container">

	<div class="ait-button item share">

		<i class="fa fa-share-alt"></i> {__ 'Share'}

		<div class="dropdown">
			<ul class="share-icons">
				<li class="share-facebook">
					<a href="#" onclick="javascript:window.open('https://www.facebook.com/sharer/sharer.php?u={!$shareUrl}', '_blank', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
					<i class="fa fa-facebook"></i>
					</a>
				</li><li class="share-twitter">
					<a href="#" onclick="javascript:window.open('https://twitter.com/intent/tweet?text={!rawurlencode($shareTitle)}&amp;url={!$shareUrl}&amp;via={!$twitterReferral}', '_blank', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
						<i class="fa fa-twitter"></i>
					</a>
				</li><li class="share-gplus">
					<a href="#" onclick="javascript:window.open('https://plus.google.com/share?url={!$shareUrl}', '_blank', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
						<i class="fa fa-google-plus"></i>
					</a>
				</li>
			</ul>
		</div>

	</div>

	<script type="text/javascript">

		jQuery(document).ready(function(){

				var shareBtn = jQuery(".ait-button.item.share");

				shareBtn.click(function(e) {
					e.preventDefault();
					shareBtn.toggleClass("hover");
				});

		});

	</script>

</div>
