<?php

if (!is_admin()) {
    return;
}

// Add a menu option "Olivia Fresh Inställningar" in the dashboard under settings
function mytheme_add_settings() {
    add_submenu_page(
        "options-general.php",
        "Olivia Fresh Inställningar",
        "Olivia Fresh Inställningar",
        "edit_pages",
        "store",
        "mytheme_add_settings_callback"
    );
}

function mytheme_add_settings_callback() {
    ?>
    <div class="wrap-settings">
        <h2>Företagsinställningar</h2>
        <form action="options.php" method="post">
            <?php
            settings_fields('store');
            do_settings_sections('store');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

add_action('admin_menu', 'mytheme_add_settings');

function mytheme_add_settings_init() {
    // General section
    add_settings_section(
        'store_general',
        'General',
        'mytheme_add_settings_section_general',
        'store'
    );

   
    // Info section
    add_settings_section(
        'address_section',
        'Info',
        'mytheme_add_settings_section_address',
        'store'
    );

    

    // Address settings
    register_setting('store', 'address');
    add_settings_field(
        'address',
        'Adress:',
        'mytheme_section_general_setting',
        'store',
        'address_section',
        array(
            "option_name" => "address",
            "option_type" => "text"
        )
    );

    register_setting('store', 'city');
    add_settings_field(
        'city',
        'Stad:',
        'mytheme_section_general_setting',
        'store',
        'address_section',
        array(
            "option_name" => "city",
            "option_type" => "text"
        )
    );

    register_setting('store', 'email');
    add_settings_field(
        'email',
        'E-post:',
        'mytheme_section_general_setting',
        'store',
        'address_section',
        array(
            "option_name" => "email",
            "option_type" => "email"
        )
    );

    register_setting('store', 'tel');
    add_settings_field(
        'tel',
        'Tel:',
        'mytheme_section_general_setting',
        'store',
        'address_section',
        array(
            "option_name" => "tel",
            "option_type" => "tel"
        )
    );
}

add_action('admin_init', 'mytheme_add_settings_init');

function mytheme_add_settings_section_general() {
    echo "<p>Generalla inställningar till företaget!</p>";
}

function mytheme_add_settings_section_address() {
    echo "<p>Generella inställningar</p>";
}

function mytheme_section_general_setting($args) {
    $option_name = $args["option_name"];
    $option_type = $args["option_type"];
    $option_value = get_option($args["option_name"]);
    echo '<input type="' . $option_type . '" id="' . $option_name . '" name="' . $option_name . '" value="' . $option_value . '" />';
}

function mytheme_image_setting($args) {
    $option_name = $args["option_name"];
    $option_value = get_option($option_name);
    echo '<input type="hidden" id="' . $option_name . '" name="' . $option_name . '" value="' . $option_value . '" />';
    echo '<img id="' . $option_name . '_preview" src="' . $option_value . '" style="max-width: 150px; display: block;" />';
    echo '<button type="button" class="button" onclick="upload_image(\'' . $option_name . '\')">Välj bild</button>';
}