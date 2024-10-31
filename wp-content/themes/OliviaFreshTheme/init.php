<?php

require_once("settings.php");
require_once("general-settings.php");
require_once("shortcodes.php");
require_once("ajax.php");

function my_theme_enqueue() {    
    $data = array(
        "name" => get_option("blogname"),
        "option" => get_option("myoption"),
        'wc_ajax_url' => admin_url( 'admin-ajax.php' ),

    );
    wp_localize_script("app", "myvariables", $data);
}
add_action('wp_enqueue_scripts', 'my_theme_enqueue');


function my_theme_init(){
    $menus = array(
        'huvudmeny' => 'huvudmeny',
        'menyikoner'=>'menyikoner',
        'footer_meny' => 'footer_meny',
     
    );

    register_nav_menus($menus);
}
add_action('after_setup_theme', 'my_theme_init');