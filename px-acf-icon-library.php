<?php
/*
Plugin Name: Pixelmatters - WordPress ACF Icon Library
Plugin URI: https://github.com/pixelmatters
Description: An WordPress plugin that adds an ACF Icon Library and Icon Picker that can be used within other ACF Field Groups
Version: 1.0.0
Author: Pixelmatters
Author URI: https://pixelmatters.com
License: MIT
License URI: http://www.gnu.org/licenses/gpl-2.0.html
GitHub Plugin URI: https://github.com/Pixelmatters/pixelmatters-wp-acf-icon-library
GitHub Branch: master
*/

require_once("helpers.php");

if (!defined('ABSPATH')) exit;

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

			$this->settings = array(
				'version'	=> '1.0.0',
				'url'		=> plugin_dir_url(__FILE__),
				'path'		=> plugin_dir_path(__FILE__)
			);

			add_action('acf/include_field_types', 	array($this, 'include_field_types'));

			add_filter('wpgraphql_acf_supported_fields', function (
				$supported_fields
			) {
				array_push($supported_fields, 'icon-picker');
				return $supported_fields;
			});

			add_filter(
				'wpgraphql_acf_register_graphql_field',
				function ($field_config, $type_name, $field_name, $config) {
					// How to add new WPGraphQL fields is super undocumented, I used this code as a base
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

						if (have_rows('icons', 'option')) {

							// while has rows
							while (have_rows('icons', 'option')) {

								// instantiate row
								the_row();
								$uniqueID = get_sub_field("unique_id");

								if ($uniqueID === $value) {
									$input_icon = get_sub_field('image') != "" ? get_sub_field('image') : null;
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

				if ('options' === $id) {
					if (have_rows('icons', 'option')) {

						while (have_rows('icons', 'option')) {

							the_row();

							$currUniqueID = get_sub_field('unique_id');

							if (!isset($currUniqueID) || $currUniqueID === "") {
								$bytes = random_bytes(16);
								$uniqueID = bin2hex($bytes);
								update_sub_field('unique_id', $uniqueID);
							}
						}
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
		}

		function include_field_types($version = false)
		{
			include_once('fields/acf-icon-picker-v5.php');
		}
	}

	add_action("init", function () {
		new px_acf_plugin_icon_library();
	}, 20);

endif;
