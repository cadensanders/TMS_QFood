<a href="#review" class="add-review ait-button item"><i class="fa fa-star"></i> {__ 'Add Review'}</a>

<script type="text/javascript">

	jQuery(document).ready(function(){

		jQuery(".add-review").on('click', function(e) {
			scrollToReview(e);
		});

	});

	function scrollToReview(e) {

		e.preventDefault();

		var reviewForm       = jQuery("#review");
		var	headerHeight     = 0;

		if (!isResponsive(980)) jQuery("#masthead .header-container").outerHeight(true);

		var	reviewFormOffset = reviewForm.offset().top - headerHeight - 35;

		jQuery("html, body").animate({ scrollTop: reviewFormOffset }, 500);

	}

</script>
