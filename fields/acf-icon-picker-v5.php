<?php
if (!defined('ABSPATH')) exit;

if (!class_exists('px_acf_field_icon_picker')) :

	class px_acf_field_icon_picker extends acf_field
	{

		function __construct($settings)
		{

			$this->name = 'icon-picker';

			$this->label = __('Icon Picker', 'acf-icon-picker');

			$this->category = 'jquery';

			$this->defaults = array(
				'initial_value'	=> '',
			);

			$this->l10n = array(
				'error'	=> __('Error!', 'acf-icon-picker'),
			);

			$this->settings = $settings;

			$this->path_suffix = apply_filters('acf_icon_path_suffix', 'assets/img/acf/');

			$this->path = apply_filters('acf_icon_path', $this->settings['path']) . $this->path_suffix;

			$this->url = apply_filters('acf_icon_url', $this->settings['url']) . $this->path_suffix;

			$priority_dir_lookup = get_stylesheet_directory() . '/' . $this->path_suffix;

			if (file_exists($priority_dir_lookup)) {
				$this->path = $priority_dir_lookup;
				$this->url = get_stylesheet_directory_uri() . '/' . $this->path_suffix;
			}

			$this->svgs = array();
			// if has rows
			if (have_rows('icons', 'option')) {

				// while has rows
				while (have_rows('icons', 'option')) {

					// instantiate row
					the_row();

					$icon = array(
						'name' => get_sub_field('title'),
						'id' => get_sub_field('unique_id'),
						'imgID' => get_sub_field('image'),
						'icon' => wp_get_attachment_url(get_sub_field('image'))
					);

					array_push($this->svgs, $icon);
				}
			}

			parent::__construct();
		}

		function render_field($field)
		{
			if (have_rows('icons', 'option')) {

				// while has rows
				while (have_rows('icons', 'option')) {

					// instantiate row
					the_row();

					if (get_sub_field("unique_id") === $field['value']) {
						$input_icon = get_sub_field('image') != "" ? wp_get_attachment_url(get_sub_field('image')) : null;
					}
				}
			}

?>
			<div class="acf-icon-picker">
				<div class="acf-icon-picker__img">
					<?php
					if (isset($input_icon) && $field['value'] != "") {
						echo '<div class="acf-icon-picker__svg">';
						echo '<img src="' . $input_icon . '" alt=""/>';
						echo '</div>';
					} else {
						echo '<div class="acf-icon-picker__svg">';
						echo '<span class="acf-icon-picker__svg--span">&plus;</span>';
						echo '</div>';
					}
					?>
					<input type="hidden" readonly name="<?php echo esc_attr($field['name']) ?>" value="<?php echo esc_attr($field['value']) ?>" />
				</div>
				<?php if ($field['required'] == false  && isset($input_icon)) { ?>
					<span class="acf-icon-picker__remove">
						Remove
					</span>
				<?php } ?>
			</div>
<?php
		}

		function input_admin_enqueue_scripts()
		{

			$url = $this->settings['url'];
			$version = $this->settings['version'];

			wp_register_script('acf-input-icon-picker', "{$url}assets/js/input.js", array('acf-input'), $version);
			wp_enqueue_script('acf-input-icon-picker');

			wp_localize_script('acf-input-icon-picker', 'iv', array(
				'path' => $this->url,
				'svgs' => $this->svgs,
				'no_icons_msg' => sprintf(esc_html__('To add icons, add them in the Icon Library.', 'acf-icon-picker'), $this->path_suffix)
			));

			wp_register_style('acf-input-icon-picker', "{$url}assets/css/input.css", array('acf-input'), $version);
			wp_enqueue_style('acf-input-icon-picker');
		}
	}
	new px_acf_field_icon_picker($this->settings);

endif;


?>