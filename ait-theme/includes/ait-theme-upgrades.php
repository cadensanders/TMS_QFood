<?php

define('AIT_UPGRADER_PREVIOUS_THEME_VERSION', '1.10');

add_action('ait-theme-upgrade', function($upgrader){
	if(version_compare($upgrader->getParentThemeVersion(), '1.10', '<')){

		$upgradeThemeFn = function(){
			$errors = array();

			// do the logic here
			$args = array(
				'posts_per_page'   => -1,
				'post_type'        => 'ait-item',
				'post_status'      => 'publish',
			);

			$items = get_posts( $args );

			foreach($items as $item){
				$meta = get_post_meta($item->ID, '_ait-item_item-data', true);
				 if(isset($meta) && !empty($meta['features'])){
 					clean_post_cache( $item->ID );

					$result = "";
					foreach ($meta['features'] as $feature) {
						$result .= $feature['text'].';'.$feature['desc'].';';
					}
					update_post_meta($item->ID, 'features_search_string', $result);
				}
			}

			/* new slugs for packages / user roles */
			// update themeoptions first
			// create new slugs for the themepackages
			global $wp_roles;
			$theme = str_replace("-child", "", sanitize_key( get_stylesheet() ) );
			$themeOptionKey = '_ait_'.$theme.'_theme_opts';	// better way to do this

			$themeOptions = get_option($themeOptionKey, array());
			foreach($themeOptions['packages']['packageTypes'] as $index => $package){
				$oldPackageSlug = "cityguide_".AitUtils::webalize($package['name']);
				$newPackageId = str_replace(".", "", uniqid("", true));
				$newPackageSlug = "cityguide_".$newPackageId;

				$themeOptions['packages']['packageTypes'][$index]['slug'] = $newPackageId;

				$oldPackageName = $package['name'];
				$oldPackageCaps = array();
				if(isset($wp_roles->roles[$oldPackageSlug])){
					$oldPackageName = $wp_roles->roles[$oldPackageSlug]['name'];
					$oldPackageCaps = $wp_roles->roles[$oldPackageSlug]['capabilities'];
					remove_role($oldPackageSlug);
				}

				add_role($newPackageSlug, $oldPackageName, $oldPackageCaps);

				// update users
				$users = get_users(array(
					'role' => $oldPackageSlug,
				));
				foreach($users as $user){
					// store old package data
					$packageActivationTime = get_user_meta($user->ID, 'package_activation_time', true);
					$packageName = get_user_meta($user->ID, 'package_name', true);

					// change old role with new role
					//$user->remove_role($oldPackageSlug);
					$user->set_role($newPackageSlug);

					// update old package data with new role
					$packageActivationTime['role'] = $newPackageSlug;
					$packageName = $newPackageSlug;

					// save updated role data
					update_user_meta($user->ID, 'package_activation_time', $packageActivationTime);
					update_user_meta($user->ID, 'package_name', $packageName);
				}

				// update item extension
				$extensionData = get_option('ait_item_extension_'.$oldPackageSlug.'_options', array());
				if(is_array($extensionData) && !empty($extensionData)){
					update_option('ait_item_extension_'.$newPackageSlug.'_options', $extensionData);
				}
			}
			update_option($themeOptionKey, $themeOptions);
			/* new slugs for packages / user roles */

			// do the logic here

			return $errors;
		};

		$upgrader->addErrors($upgradeThemeFn());
	}
});

add_action('ait-theme-upgrade', function($upgrader){
	if(version_compare($upgrader->getParentThemeVersion(), '1.11', '<')){

		$upgradeThemeFn = function(){
			$errors = array();

			// do the logic here
			/* new slugs for packages / user roles */
			// update themeoptions first
			// create new slugs for the themepackages
			global $wp_roles;
			$theme = sanitize_key( get_stylesheet() );
			$themeOptionKey = '_ait_'.$theme.'_theme_opts';	// better way to do this

			$themeOptions = get_option($themeOptionKey, array());
			if(!empty($themeOptions)){
				foreach($themeOptions['packages']['packageTypes'] as $index => $package){
					// if there is no slug present in the database => run the function again
					// child theme problem _ait_<childtheme>_theme_opts vs _ait_<parenttheme>_theme_opts problem
					if(empty($themeOptions['packages']['packageTypes'][$index]['slug'])){

						$oldPackageSlug = "cityguide_".AitUtils::webalize($package['name']);
						$newPackageId = str_replace(".", "", uniqid("", true));
						$newPackageSlug = "cityguide_".$newPackageId;

						$themeOptions['packages']['packageTypes'][$index]['slug'] = $newPackageId;

						$oldPackageName = $package['name'];
						$oldPackageCaps = array();
						if(isset($wp_roles->roles[$oldPackageSlug])){
							$oldPackageName = $wp_roles->roles[$oldPackageSlug]['name'];
							$oldPackageCaps = $wp_roles->roles[$oldPackageSlug]['capabilities'];
							remove_role($oldPackageSlug);
						}

						add_role($newPackageSlug, $oldPackageName, $oldPackageCaps);

						// update users
						$users = get_users(array(
							'role' => $oldPackageSlug,
						));
						foreach($users as $user){
							// store old package data
							$packageActivationTime = get_user_meta($user->ID, 'package_activation_time', true);
							$packageName = get_user_meta($user->ID, 'package_name', true);

							// change old role with new role
							//$user->remove_role($oldPackageSlug);
							$user->set_role($newPackageSlug);

							// update old package data with new role
							$packageActivationTime['role'] = $newPackageSlug;
							$packageName = $newPackageSlug;

							// save updated role data
							update_user_meta($user->ID, 'package_activation_time', $packageActivationTime);
							update_user_meta($user->ID, 'package_name', $packageName);
						}

						// update item extension
						$extensionData = get_option('ait_item_extension_'.$oldPackageSlug.'_options', array());
						if(is_array($extensionData) && !empty($extensionData)){
							update_option('ait_item_extension_'.$newPackageSlug.'_options', $extensionData);
						}
					}
				}
				update_option($themeOptionKey, $themeOptions);
			}
			/* new slugs for packages / user roles */

			// do the logic here

			return $errors;
		};

		$upgrader->addErrors($upgradeThemeFn());
	}
});
?>