{* VARIABLES *}
{var $settings = $options->theme->items}
{var $sortingDefaults = aitConfig()->getRawConfig()}
{var $filterCounts = $sortingDefaults[theme][items][options][sortingDefaultCount][default]}
{var $filterOrderBy = array(
	array("date", "Date", "fa fa-clock-o"),
	array("title", "Title", "fa fa-font"),
	array("comment_count", "Comments Count", "fa fa-comments")
)}

{if defined('AIT_REVIEWS_ENABLED')}
	{var $filterOrderBy[] = array("rating", "Rating", "fa fa-star")}
{/if}

{var $filterCountsSelected = isset($_REQUEST['count']) && $_REQUEST['count'] != "" ? $_REQUEST['count'] : $settings->sortingDefaultCount}
{var $filterOrderBySelected = isset($_REQUEST['orderby']) && $_REQUEST['orderby'] != "" ? $_REQUEST['orderby'] : $settings->sortingDefaultOrderBy}
{var $filterOrderSelected = isset($_REQUEST['order']) && $_REQUEST['order'] != "" ? $_REQUEST['order'] : $settings->sortingDefaultOrder}
{* VARIABLES *}

<div class="filters-wrap">
	<h2><span class="items-count">{!$max}</span>{!__ 'Items'}</h2>
	<a href="#" class="ait-toggle-area-btn" data-toggle='[".filters-container", ".advanced-filters-wrap"]'>{__ 'Toggle Filters'}</a>
	<div class="filters-container ait-toggle-area">
		<div class="content">
			<div class="filter-container filter-count" data-filterid="count">
				<div class="content">
					<div class="selected">{__ 'Count'}</div>
					<select class="filter-data">
						{foreach $filterCounts as $filter}
							{if $filter == $filterCountsSelected}
								<option value="{$filter}" selected>{$filter}</option>
							{else}
								<option value="{$filter}">{$filter}</option>
							{/if}
						{/foreach}
					</select>
				</div>
			</div>
			<div class="filter-container filter-orderby" data-filterid="orderby">
				<div class="content">
					<div class="selected">{__ 'Sort by'}</div>
					<select class="filter-data">
						{foreach $filterOrderBy as $filter}
							{if $filter[0] == $filterOrderBySelected}
								<option value="{$filter[0]}" data-icon="{$filter[2]}" data-text="{$filter[1]}" selected>{$filter[1]}</option>
							{else}
								<option value="{$filter[0]}" data-icon="{$filter[2]}" data-text="{$filter[1]}">{$filter[1]}</option>
							{/if}
						{/foreach}
					</select>
				</div>
			</div>
			<div class="filter-container filter-order" data-filterid="order">
				<div class="content">
					<div class="selected title">{__ 'Order'}</div>
					<a n:class='$filterOrderSelected == "ASC" ? selected' title="ASC" href="#" data-value="ASC"><i class="fa fa-caret-down"></i></a><!--
					--><a n:class='$filterOrderSelected == "DESC" ? selected' title="DESC" href="#" data-value="DESC"><i class="fa fa-caret-up"></i></a>
				</div>
			</div>
			<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery('.filter-container').each(function(){
					$select = jQuery(this).find('select');
					$select.change(function(){
						getItems();
					});
					$order = jQuery(this).find('a');
					$order.click(function(e){
						e.preventDefault();
						$order.parent().find('.selected').removeClass('selected');
						jQuery(this).addClass('selected');
						getItems();
					})
				});

				jQuery('.filter-orderby select').on('change', function(){
					var $sbSelector = jQuery('.filter-orderby .sbSelector');
					var $selectSelected = jQuery(this).find('option:selected');
					$sbSelector.html('<i class="'+$selectSelected.data('icon')+'"></i>');
				});
			});

			jQuery(window).load(function(){
				var $sbSelector = jQuery('.filter-orderby .sbSelector');
				var $selectSelected = jQuery('.filter-orderby select option:selected');
				$sbSelector.html('<i class="'+$selectSelected.data('icon')+'"></i>');
			});

			function getItems(){
				// defaults
				var data = {
					count: 10,
					orderby: 'date',
					order: 'ASC'
				}
				jQuery('.filter-container').each(function(){
					var key = jQuery(this).data('filterid');
					if(key == "order"){
						var val = jQuery(this).find('a.selected').data('value');
					} else {
						var val = jQuery(this).find('select option:selected').attr('value');
					}
					data[key] = val;
				});

				// build url
				var baseUrl = window.location.protocol+"//"+window.location.host+window.location.pathname;
				var eParams = window.location.search.replace("?", "").split('&');
				var nParams = {};
				jQuery.each(eParams, function(index, value){
					var val = value.split("=");
					if(typeof val[1] == "undefined"){
						nParams[val[0]] = "";
					} else {
						nParams[val[0]] = decodeURI(val[1]);
					}
				});
				var query = jQuery.extend({}, nParams, data);
				var queryString = jQuery.param(query);
				window.location.href = baseUrl + "?" + queryString;
			}
			</script>
		</div>
	</div>
</div>
