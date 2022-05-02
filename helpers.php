<?php

/**
 * ADAPTED GIST FROM: https://gist.github.com/dianjuar/9a398c9e86a20a30868eee0c653e0ca4
 */
function px_required_plugins_active($plugin, $required_plugins, $textdomain = '')
{

    # Needed to the function "deactivate_plugins" works
    include_once(ABSPATH . 'wp-admin/includes/plugin.php');

    $allActive = true;
    $inactivePlugins = [];

    foreach ($required_plugins as $requiredPlugin) {
        $validPath = array_key_exists("path", $requiredPlugin) && is_plugin_active($requiredPlugin["path"]);
        $validClass = array_key_exists("class", $requiredPlugin) && class_exists($requiredPlugin["class"]);

        if (!$validPath && !$validClass) {
            $allActive = false;
            array_push($inactivePlugins, $requiredPlugin);
        }
    }

    if (!$allActive) {
        # Deactivate the current plugin
        deactivate_plugins($plugin["path"]);

        # Show an error alert on the admin area
        add_action('admin_notices', function () use ($plugin, $inactivePlugins, $textdomain) {
            $inactivePluginsTitles = [];

            foreach ($inactivePlugins as $inactivePlugin) {
                array_push($inactivePluginsTitles, $inactivePlugin["name"]);
            }

?>
            <div class="updated error">
                <p>
                    <?php
                    echo sprintf(
                        __('The plugin <strong>"%s"</strong> needs the follwing plugins: <strong>"%s"</strong> active', $textdomain),
                        $plugin["name"],
                        implode(", ", $inactivePluginsTitles)
                    );
                    echo '<br>';
                    echo sprintf(
                        __('<strong>%s has been deactivated</strong>', $textdomain),
                        $plugin["name"]
                    );
                    ?>
                </p>
            </div>
<?php
            if (isset($_GET['activate']))
                unset($_GET['activate']);
        });
    }
}


/* Register site options page */

function add_acf_icon_library_options_page()
{
    acf_add_options_page(array(
        'page_title' => 'Icon Library',
        'menu_title' => 'Icon Library',
        'menu_slug' => 'icon-library',
        'capability' => 'edit_posts',
        'redirect'     => false,
        'autoload' => true,
        'show_in_graphql' => true,
    ));


    acf_add_local_field_group(array (
        'key' => 'icon_library',
        'title' => 'Settings: Icon Library',
        'fields' => array (
            array (
                'key' => 'icons_key',
                'label' => 'Icons',
                'name' => 'icons',
                'type' => 'repeater',
                'prefix' => '',
                'instructions' => '',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'collapsed' => '',
                'min' => 0,
                'max' => 0,
                'layout' => 'table',
                'button_label' => '',
                'sub_fields' => array(
                    array(
                        'key' => 'icon_id_key',
                        'label' => 'ID',
                        'name' => 'unique_id',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),
                    array(
                        'key' => 'icon_title_key',
                        'label' => 'Title',
                        'name' => 'title',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),
                    array(
                        'key' => 'icon_image_key',
                        'label' => 'Image',
                        'name' => 'image',
                        'type' => 'image_aspect_ratio_crop',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'crop_type' => "aspect_ratio",
                        'aspect_ratio_width' => 144,
                        'aspect_ratio_height' => 144,
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                    ),
                ),
			),
        ),
        'location' => array (
            array (
                array (
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'icon-library',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'show_in_graphql' => true,
        'graphql_field_name' => "iconLibraryData",

    ));
}

if (function_exists('acf_add_local_field_group')) {
    add_action('acf/init', 'add_acf_icon_library_options_page');
}
?>