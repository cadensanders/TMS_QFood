<?php

function aitGetItemsMarkers($query)
{
    $markers = array();

    foreach (new WpLatteLoopIterator($query) as $item) {
        $meta = $item->meta('item-data');

        $price = get_post_meta( $item->id, '_ait-item_food-options_price', true );

        $options = aitOptions()->getOptionsByType('theme');
		$featuredImage = $item->imageUrl;
        $imageLink = empty($featuredImage) ? $options['item']['noFeatured'] : $item->imageUrl;

        $context = aitRenderLatteTemplate('/parts/item-marker.php', array('item' => $item));
        $catData = aitItemCategoriesData($item->id);
        $marker = (object)array(
            'lat'        => $meta->map['latitude'],
            'lng'        => $meta->map['longitude'],
            'title'      => $item->rawTitle,
            'icon'       => $catData['icon'],
            'context'    => $context,
            'type'       => 'item',
            'data'       => array(
                'categories' => $catData['parents'],
                'price'      => empty($price) ? '0' : $price
            ),
        );
        array_push($markers, $marker);
    }
    return $markers;
}

function aitItemCategoriesData($itemID)
{
    $options = aitOptions()->getOptionsByType('theme');
    $itemCats = get_the_terms($itemID, 'ait-items');
    $itemLocations = get_the_terms($itemID, 'ait-locations');
    $icon = aitPaths()->url->img . "/pins/default_pin.png";
    $parents = array();

    if (!$itemCats) {
        $itemCats = array();
    }
    if (!$itemLocations) {
        $itemLocations = array();
    }

    foreach ($itemCats as $cat) {
        $parent = get_term($cat->parent, 'ait-items');
        $catOption = get_option('ait-items_category_'.$cat->term_id);

        if (!empty($catOption['map_icon'])) {
            $icon = $catOption['map_icon'];
        } elseif(isset($parent) && !($parent instanceof WP_Error)) {
            $parentOption = get_option('ait-items_category_'.$parent->term_id);
            array_push($parents, $parent->term_id);
            if (!empty($parentOption['map_icon'])) {
                $icon = $parentOption['map_icon'];
            }
        }

        if(isset($parent) && !($parent instanceof WP_Error) && !in_array($parent->term_id, $parents)) {
            array_push($parents, $parent->term_id);
        } else {
            array_push($parents, $cat->term_id);
        }
    }

    foreach ($itemLocations as $cat) {
        $parent = get_term($cat->parent, 'ait-locations');

        if(isset($parent) && !($parent instanceof WP_Error) && !in_array($parent->term_id, $parents)) {
            array_push($parents, $parent->term_id);
        } else {
            array_push($parents, $cat->term_id);
        }
    }

    return array(
        'icon' => $icon,
        'parents' => $parents,
    );
}

// allows programmers to include part in the same way as in latte syntax
function aitRenderLatteTemplate($template, $params = array())
{
    AitWpLatte::init();
    ob_start();
    WpLatte::render(aitPath('theme', $template), $params);
    $result = ob_get_contents();
    ob_end_clean();
    return $result;
}

function aitGetMapOptions($options)
{
    $result = array();
    $result['styles'] = aitGetMapStyles($options);

    if (!isset($options['autoZoomAndFit']) || !$options['autoZoomAndFit']) {
        $result['center'] = array(
            'lat' => floatval($options['address']['latitude']),
            'lng' => floatval($options['address']['longitude']),
        );
    }

    if (!empty($options['mousewheelZoom'])) {
        $result['scrollwheel'] = true;
    }

    if (isset($options['zoom'])) {
        $result['zoom'] = intval($options['zoom']);
    }

    return $result;
}

function aitGetMapStyles($options)
{
    $o = $options;
    $styles = array(
    	array(
    		'stylers' => array(
                array('hue'        => $o['mapHue']),
                array('saturation' => $o['mapSaturation']),
                array('lightness'  => $o['mapBrightness']),
    		),
    	),
    	array(
    		'featureType' => 'landscape',
    		'stylers' => array(
                array('visibility' => $o['landscapeShow'] == false ? 'off' : 'on'),
                array('hue'        => $o['landscapeColor']),
                array('saturation' => $o['landscapeColor'] != '' ? $o['objSaturation'] : ''),
                array('lightness'  => $o['landscapeColor'] != '' ? $o['objBrightness'] : ''),
    		),
    	),
        array(
            'featureType' => 'administrative',
            'stylers' => array(
                array('visibility' => $o['administrativeShow'] == false ? 'off' : 'on'),
                array('hue'        => $o['administrativeColor']),
                array('saturation' => $o['administrativeColor'] != '' ? $o['objSaturation'] : ''),
                array('lightness'  => $o['administrativeColor'] != '' ? $o['objBrightness'] : ''),
            ),
        ),
        array(
            'featureType' => 'road',
            'stylers' => array(
                array('visibility' => $o['roadsShow'] == false ? 'off' : 'on'),
                array('hue'        => $o['roadsColor']),
                array('saturation' => $o['roadsColor'] != '' ? $o['objSaturation'] : ''),
                array('lightness'  => $o['roadsColor'] != '' ? $o['objBrightness'] : ''),
            ),
        ),
        array(
            'featureType' => 'water',
            'stylers' => array(
                array('visibility' => $o['waterShow'] == false ? 'off' : 'on'),
                array('hue'        => $o['waterColor']),
                array('saturation' => $o['waterColor'] != '' ? $o['objSaturation'] : ''),
                array('lightness'  => $o['waterColor'] != '' ? $o['objBrightness'] : ''),
            ),
        ),
        array(
            'featureType' => 'poi',
            'stylers' => array(
                array('visibility' => $o['poiShow'] == false ? 'off' : 'on'),
                array('hue'        => $o['poiColor']),
                array('saturation' => $o['poiColor'] != '' ? $o['objSaturation'] : ''),
                array('lightness'  => $o['poiColor'] != '' ? $o['objBrightness'] : ''),
            ),
        ),
    );
    return $styles;
}

add_action( 'save_post', 'aitSaveItemMeta', 13, 2 );
function aitSaveItemMeta( $post_id, $post )
{
    $slug = 'ait-item';

    if ( $slug != $post->post_type ) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Prevent quick edit from clearing custom fields
    if (defined('DOING_AJAX') && DOING_AJAX) {
        return;
    }

    // save separated meta data for featured
    if(!empty($_POST)){ // bulk edit check ... in this case $_POST is still empty
        if(isset($_POST['_ait-item_item-data']['featuredItem'])){
            if (intval($_POST['_ait-item_item-data']['featuredItem']) == 1) {
                update_post_meta($post_id, '_ait-item_item-featured', '1');
            }
            else {
                update_post_meta($post_id, '_ait-item_item-featured', '0');
            }
        } else {
            // item created with directory role that cannot set item as featured
            update_post_meta($post_id, '_ait-item_item-featured', '0');
        }
    }

	// save separated meta data for features
	// the reason is to include features in search by keyword
	// we can chain all feature labels and descriptions to one long text because we anyway search by %LIKE%
    if(!empty($_POST)){ // bulk edit check ... in this case $_POST is still empty
        if(!empty($_POST['_ait-item_item-data']['features'])){
			$result = "";
			foreach ($_POST['_ait-item_item-data']['features'] as $feature) {
				$result .= $feature['text'].';'.$feature['desc'].';';
			}
			update_post_meta($post_id, 'features_search_string', $result);
        } else {
            // we don't need meta if features are empty
			delete_post_meta($post_id, 'features_search_string');
        }
    }

    // if item hasn't been rated yet, create rating manually
    if (get_post_meta( $post_id, 'rating_mean', true ) == '') {
        update_post_meta($post_id, 'rating_mean', '0');
    }
}



// save custom meta for items created via CSV importer
add_action('ait_csv_post_imported', function($post_type, $post_id){
    // quit if post isn't ait-item
    if ( $post_type != 'ait-item' ) {
        return;
    }

    $meta = get_post_meta($post_id, '_ait-item_item-data', true);
    // quit if post doesn't have meta for some reason
    if(empty($meta)){
        $return;
    }

	if(!empty($meta['features'])){
		$result = "";
		foreach ($meta['features'] as $feature) {
			$result .= $feature['text'].';'.$feature['desc'].';';
		}
        update_post_meta($post_id, 'features_search_string', $result);
    } else {
		delete_post_meta($post_id, 'features_search_string');
	}
}, 10, 2 );


function aitMapTranslations()
{
    return array(
        'error_geolocation_failed' => __("This page has been blocked from tracking your location", "ait"),
        'error_geolocation_unsupported' => __("Your browser doesn't support geolocation", "ait"),
    );
}


add_filter( 'ait_alter_search_query', function($query){
    /* VARIABLES */
    $settings = aitOptions()->getOptionsByType('theme');
    $settings = (object)$settings['items'];
    $filterCountsSelected = isset($_REQUEST['count']) && $_REQUEST['count'] != "" ? $_REQUEST['count'] : $settings->sortingDefaultCount;
    $filterOrderBySelected = isset($_REQUEST['orderby']) && $_REQUEST['orderby'] != "" ? $_REQUEST['orderby'] : $settings->sortingDefaultOrderBy;
    $filterOrderSelected = isset($_REQUEST['order']) && $_REQUEST['order'] != "" ? $_REQUEST['order'] : $settings->sortingDefaultOrder;

    $itemsList = array();
    $taxQuery  = array();
    $metaQuery = array(
        'relation' => 'AND',
        'featured_clause' => array(
            'key'   => '_ait-item_item-featured',
            'compare' => 'EXISTS'
        )
    );

    $query->set('lang', AitLangs::getCurrentLanguageCode());

	/* APPLY REVIEWS ORDERING FOR ITEMS */
    if (defined('AIT_REVIEWS_ENABLED') and $filterOrderBySelected == 'rating') {
        $metaQuery['rating_clause'] = array(
            'key' => 'rating_mean',
            'compare' => 'EXISTS'
        );
        $filterOrderBySelected = 'rating_clause';
    }

    /* APPLY ADVANCED FILTERS */
    if (!empty($_REQUEST['filters'])) {
        $metaQuery = aitFilterByAdvancedFilters( $metaQuery, $_REQUEST['filters'] );
    }

    /* IS SEARCH PAGE */
    if(isset($_REQUEST['a']) && $_REQUEST['a'] == true) {
        if(defined('AIT_FOOD_MENU_ENABLED')) {
            // filter by price level
            if (!empty($_REQUEST['price'])) {
                $metaQuery['price_clause'] = array(
                    'key'     => '_ait-item_food-options_price',
                    'value'   => $_REQUEST['price'],
                    'compare' => '='
                );
            }
        }


        /* FILTER BY TAXONOMIES */
        if (!empty($_REQUEST['category'])) {
            array_push($taxQuery, array('taxonomy' => 'ait-items', 'field' => 'term_id', 'terms' => $_REQUEST['category']));
        }
        if (!empty($_REQUEST['location'])){
            array_push($taxQuery, array('taxonomy' => 'ait-locations', 'field' => 'term_id', 'terms' => $_REQUEST['location']));
        }

        /* FILTER BY RADIUS */
        if (!empty($_REQUEST['lat']) && !empty($_REQUEST['lon']) and !empty($_REQUEST['rad'])) {
            $radiusUnits = !empty($_REQUEST['runits']) ? $_REQUEST['runits'] : 'km';
            $radiusValue = !empty($_REQUEST['rad']) ? $_REQUEST['rad'] * 1000 : 100 * 1000;
            $radiusValue = $radiusUnits == 'mi' ? $radiusValue * 1.609344 : $radiusValue;

            $latitude = $_REQUEST['lat'];
            $longitude = $_REQUEST['lon'];

            $query->set('post_type', 'ait-item');
            $query->set('posts_per_page', -1);
            $query->set('meta_query', $metaQuery);
            $query->set('tax_query', $taxQuery);

			if (!empty($_REQUEST['s'])) {
				add_filter( 'posts_where', 'aitIncludeMetaInSearch' );
				// include restaurants(items) with menus that contain searched keyword
				if(defined('AIT_FOOD_MENU_ENABLED')) {
					add_filter('posts_where', 'aitIncludeItemsByMenu');
				}
			}
            $queryToFilter = new WP_Query($query->query_vars);
            $filteredByRadiusList = aitFilterByRadius($queryToFilter, $radiusValue, $latitude, $longitude);

            /* if $filteredByRadiusList is empty it means there are no items in result */
            if (empty($filteredByRadiusList)) {
               $filteredByRadiusList = array(0);
            }

            wp_reset_query();
            $query->set('post__in', $filteredByRadiusList);
        } else {
            $query->set('meta_query', $metaQuery);
            $query->set('tax_query', $taxQuery);
            // $query->set('post__in', $itemsList);
        }

        $query->set('post_type', 'ait-item');
        $query->set('posts_per_page', $filterCountsSelected);
        $query->set('orderby', array(
            'featured_clause' => 'DESC',
            $filterOrderBySelected => $filterOrderSelected
        ));

		// we must add filters every time we do new wp query
		if(!empty($_REQUEST['s'])) {
            add_filter( 'posts_where', 'aitIncludeMetaInSearch' );
            // include restaurants(items) with menus that contain searched keyword
            if(defined('AIT_FOOD_MENU_ENABLED')) {
                add_filter('posts_where', 'aitIncludeItemsByMenu');
            }
        }

    /* IS TAXONOMY PAGE */
    } elseif ($query->is_tax('ait-items') || $query->is_tax('ait-locations')) {
        $query->set('posts_per_page', $filterCountsSelected);
        $query->set('meta_query', $metaQuery);
        $query->set('orderby', array(
            'featured_clause' => 'DESC',
            $filterOrderBySelected => $filterOrderSelected
        ));
    }
    return $query;
} );

function aitIncludeMetaInSearch($where) {
	remove_filter('posts_where', 'aitIncludeMetaInSearch');
	global $wpdb;

	// list of meta keys where we are searching
	$metaKeys = array( 'features_search_string');
	$metaKeys = "'" . implode( "', '", esc_sql( $metaKeys ) ) . "'";

    // Get the start of the query, where string is searched in title
    $toFind = "(".$wpdb->posts . ".post_title";
	$pos = strpos($where, $toFind);
	$startOfQuery = substr( $where, 0, $pos );
    $restOfQuery = substr( $where ,$pos );

	$likeClause = $wpdb->esc_like( get_query_var( 's' ) );
	$likeClause = '%' . $likeClause . '%';

	// search in postmeta values and return array of post ids
	$subQuery = $wpdb->prepare(
		"SELECT group_concat(post_id) as ids FROM {$wpdb->postmeta} AS postmeta
			WHERE postmeta.meta_value LIKE %s
			AND postmeta.meta_key IN ({$metaKeys})",
		$likeClause
	);

    // Inject our WHERE clause in between the start of the query and the rest of the query
    $where = $startOfQuery."(FIND_IN_SET(".$wpdb->posts.".ID, (".$subQuery."))) OR ".$restOfQuery;

    // Return revised WHERE clause
    return $where;
}

function aitIncludeItemsByMenu($where) {
	remove_filter('posts_where', 'aitIncludeItemsByMenu');
	global $wpdb;
    $args = array(
        'post_type' => 'ait-food-menu',
        's' => $_REQUEST['s'],
        'posts_per_page' => -1,
        'fields' => 'ids',
		'lang' => AitLangs::getCurrentLanguageCode(),
    );
    $menusList = get_posts($args);

    $menusList = implode(', ', $menusList);

    // Get the start of the query, where string is searched in title
    $toFind = "(".$wpdb->posts . ".post_title";
	$pos = strpos($where, $toFind);
	$startOfQuery = substr( $where, 0, $pos );
    $restOfQuery = substr( $where ,$pos );

    // search in postmeta values and return array of post ids
	$subQuery = $wpdb->prepare(
		"SELECT group_concat(meta_value) FROM {$wpdb->postmeta} AS postmeta
			WHERE postmeta.post_id IN (%s)
			AND postmeta.meta_key = 'ait-food-menu-item'",
		$menusList
	);
    // Inject our WHERE clause in between the start of the query and the rest of the query
    $where = $startOfQuery."(".$wpdb->posts.".ID IN (".$subQuery.")) OR ".$restOfQuery;

    // Return revised WHERE clause
    return $where;
}



function aitFilterByRadius($query, $radiusValue, $latitude, $longitude)
{
    $metaKey = 'item-data';

    $filtered = array();
    foreach (new WpLatteLoopIterator($query) as $item) {
        $meta = $item->meta($metaKey);
        if (isPointInRadius($radiusValue, $latitude, $longitude, $meta->map['latitude'], $meta->map['longitude'])){
            array_push($filtered, $item->id);
        }
    }
    return $filtered;
}



function aitFilterByAdvancedFilters($metaQuery, $filters)
{
    $filters = explode(";", $filters);

    foreach ($filters as $key => $value) {
        array_push($metaQuery, array(
            'key' => '_ait-item_filters-options',
            'value' => '"'.$value.'"',
            'compare' => 'LIKE',
        ));
    }

    return $metaQuery;
}


function aitGetTermPostCount( $taxonomy = 'category', $term = '', $args = array() )
{
    // Lets first validate and sanitize our parameters, on failure, just return false
    if ( !$term )
        return false;

    if ( $term !== 'all' ) {
        if ( !is_array( $term ) ) {
            $term = filter_var(       $term, FILTER_VALIDATE_INT );
        } else {
            $term = filter_var_array( $term, FILTER_VALIDATE_INT );
        }
    }

    if ( $taxonomy !== 'category' ) {
        $taxonomy = filter_var( $taxonomy, FILTER_SANITIZE_STRING );
        if ( !taxonomy_exists( $taxonomy ) )
            return false;
    }

    if ( $args ) {
        if ( !is_array )
            return false;
    }

    // Now that we have come this far, lets continue and wrap it up
    // Set our default args
    $defaults = array(
        'posts_per_page' => 1,
        'fields'         => 'ids'
    );

    if ( $term !== 'all' ) {
        $defaults['tax_query'] = array(
            array(
                'taxonomy' => $taxonomy,
                'terms'    => $term
            )
        );
    }
    $combined_args = wp_parse_args( $args, $defaults );
    $q = new WP_Query( $combined_args );

    // Return the post count
    return $q->found_posts;
}