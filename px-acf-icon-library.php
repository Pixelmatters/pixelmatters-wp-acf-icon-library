<?php
/*
Plugin Name: Pixelmatters - WordPress ACF PRO Icon Library
Plugin URI: https://github.com/Pixelmatters/pixelmatters-wp-acf-icon-library
Description: An WordPress plugin that adds an ACF PRO Icon Library and Icon Picker that can be used within other ACF Field Groups
Version: 1.3.0
Requires at least: 5.0
Requires PHP: 7.2
Author: Pixelmatters
Author URI: https://pixelmatters.com
License: MIT
License URI: http://www.gnu.org/licenses/gpl-2.0.html
GitHub Plugin URI: https://github.com/Pixelmatters/pixelmatters-wp-acf-icon-library
GitHub Branch: main
*/

if (!defined('ABSPATH')) exit;

require_once("helpers.php");

if (!class_exists('px_acf_plugin_icon_library')) :

	class px_acf_plugin_icon_library
	{

		function __construct()
		{
			$plugin = array(
				"name" => "Pixelmatters - WordPress ACF Icon Library",
				"path" => "px-acf-icon-library/px-acf-icon-library.php",
			);

			$requiredPlugins = array(
				["name" => "Advanced Custom Fields PRO", "class" => "ACF"],
				["name" => "WPGraphQL", "class" => "WPGraphQL"],
				["name" => "WPGraphQL - ACF", "path" => "wp-graphql-acf/wp-graphql-acf.php"],
				["name" => "ACF - Image Aspect Ratio Crop", "class" => "npx_acf_plugin_image_aspect_ratio_crop"],
			);

			px_required_plugins_active($plugin, $requiredPlugins, "");

			add_option("px_acf_icon_library_images", []);

			$this->settings = array(
				'version'	=> '1.0.0',
				'url'		=> plugin_dir_url(__FILE__),
				'path'		=> plugin_dir_path(__FILE__)
			);

			add_filter('wpgraphql_acf_supported_fields', function (
				$supported_fields
			) {
				array_push($supported_fields, 'icon-picker');
				return $supported_fields;
			});

			add_filter(
				'wpgraphql_acf_register_graphql_field',
				function ($field_config, $type_name, $field_name, $config) {
					// How to add new WPGraphQL fields is super undocumented, this code was used as a base
					// https://github.com/wp-graphql/wp-graphql/issues/214#issuecomment-653141685
					$acf_field = isset($config['acf_field'])
						? $config['acf_field']
						: null;
					$acf_type = isset($acf_field['type'])
						? $acf_field['type']
						: null;

					$resolve = $field_config['resolve'];

					if ($acf_type == 'icon-picker') {
						$field_config = [
							'type' => 'MediaItem',
							'resolve' => function (
								$root,
								$args,
								$context,
								$info
							) use ($resolve) {
								$value = $resolve($root, $args, $context, $info);
								return WPGraphQL\Data\DataSource::resolve_post_object(
									(int) $value,
									$context
								);
							},
						];
					}

					return $field_config;
				},
				10,
				4
			);

			add_filter(
				'graphql_acf_field_value',
				function ($value, $acf_field) {
					$text_types = ['icon-picker'];
					$input_icon = $value;
					if (in_array($acf_field['type'], $text_types)) {
						if (have_rows('icons', 'options')) {

							// while has rows
							while (have_rows('icons', 'options')) {

								// instantiate row
								the_row();
								$uniqueID = get_sub_field("unique_id");

								if ($uniqueID === $value) {
									$image = get_sub_field('image');

									$input_icon = isset($image) && $image["url"] != "" ? get_sub_field('image')["id"] : null;
								}
							}
						}
					}

					return $input_icon;
				},
				10,
				2
			);

			// ADD A UNIQUE ID TO EACH ICON IN THE ICON LIBRARY ON SAVE
			function set_unique_ids($id)
			{
				$imagesArr = [];

				if ('options' === $id) {
					if (have_rows('icons', 'options')) {

						while (have_rows('icons', 'options')) {

							the_row();

							$currUniqueID = get_sub_field('unique_id');

							if (!isset($currUniqueID) || $currUniqueID === "") {
								$bytes = random_bytes(16);
								$uniqueID = bin2hex($bytes);
								update_sub_field('unique_id', $uniqueID);
							}

							$image = get_sub_field('image');
							array_push($imagesArr, $image["id"]);
						}
						update_option("px_acf_icon_library_images", $imagesArr);
					}
				}



				return $id;
			}

			add_filter('acf/save_post', 'set_unique_ids', 10, 1);

			// PREVENT UNIQUE ID FROM BEING EDITABLE BUT STILL VISIBLE AND VALID
			function px_acf_admin_head()
			{
?>
				<style type="text/css">
					td[data-name="unique_id"] input {
						pointer-events: none;
						opacity: 0.3;
					}
				</style>
<?php
			}
			add_action('acf/input/admin_head', 'px_acf_admin_head');
			add_action('acf/include_field_types', array($this, 'include_field_types'));
			add_action('acf/input/admin_enqueue_scripts', array($this, "px_acf_icon_library_load_script"));
		}

		function include_field_types($version = false)
		{
			include_once('fields/acf-icon-picker-v5.php');
		}

		/**
		 * Load the JS for the Icon Library
		 */
		function px_acf_icon_library_load_script()
		{
			$url = $this->settings['url'];
			$version = $this->settings['version'];

			wp_enqueue_script('acf-icon-library-script', "{$url}assets/js/library.js", array('acf-input'), $version);
		}
	}


	add_action('plugins_loaded', function () {
		new px_acf_plugin_icon_library();
	}, 20);


	/**
	 * Hide Icon Library Images from Media Library to prevent accidental deletion
	 */

	add_filter('ajax_query_attachments_args', 'hide_icon_library_attachments', 10, 1);

	function hide_icon_library_attachments($query)
	{
		$imagesArr = get_option("px_acf_icon_library_images");
		$query['meta_query'] = [];
		$query['post__not_in'] = $imagesArr;

		return $query;
	}

	add_filter('pre_get_posts', '_wp_media_pre_get_posts');

	function _wp_media_pre_get_posts($wp_query)
	{
		global $pagenow;

		$imagesArr = get_option("px_acf_icon_library_images");

		if (!in_array($pagenow, array('upload.php', 'admin-ajax.php')))
			return;

		$query['meta_query'] = [];
		$wp_query->set('post__not_in', $imagesArr);
	}

endif;
