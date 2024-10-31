<?php

if (!is_admin()) {
    return;
}

// Add a menu option "Olivia Fresh Logo" in the dashboard under settings
function mytheme_add_logo_settings() {
    add_submenu_page(
        "options-general.php",
        "Olivia Fresh Logo",
        "Olivia Fresh Logo",
        "manage_options",
        "logo_settings",
        "mytheme_add_logo_settings_callback"
    );
}

function mytheme_add_logo_settings_callback() {
    ?>
    <div class="wrap-settings">
        <h2>Logo Inställningar</h2>
        <form action="options.php" method="post">
            <?php
            settings_fields('logo_settings');
            do_settings_sections('logo_settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

add_action('admin_menu', 'mytheme_add_logo_settings');

function mytheme_add_logo_settings_init() {
    // Logo section
    add_settings_section(
        'logo_section',
        'Logo',
        'mytheme_add_logo_section',
        'logo_settings'
    );

    // Logo setting
    register_setting('logo_settings', 'site_logo');
    add_settings_field(
        'site_logo',
        'Site Logo:',
        'mytheme_image_setting',
        'logo_settings',
        'logo_section',
        array(
            "option_name" => "site_logo"
        )
    );
}

add_action('admin_init', 'mytheme_add_logo_settings_init');

function mytheme_add_logo_section() {
    echo "<p>Inställningar för webbplatsens logotyp.</p>";
}

if (!function_exists('mytheme_image_setting')) {
    function mytheme_image_setting($args) {
        $option_name = $args["option_name"];
        $option_value = get_option($option_name);
        echo '<input type="hidden" id="' . $option_name . '" name="' . $option_name . '" value="' . $option_value . '" />';
        echo '<img id="' . $option_name . '_preview" src="' . $option_value . '" style="max-width: 150px; display: block;" />';
        echo '<button type="button" class="button" onclick="upload_image(\'' . $option_name . '\')">Välj bild</button>';
    }
}

?>


