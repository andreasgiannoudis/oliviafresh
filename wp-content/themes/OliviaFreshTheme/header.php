<html>

<head>
    <title><?= get_option("blogname"); ?></title>
    <?php wp_head(); ?>
    <script src="https://kit.fontawesome.com/c00b9243bd.js" crossorigin="anonymous"></script>
</head>

<body>
    <?php wp_body_open(); ?>
    <?php if (!empty(get_option('store_message'))) : ?>
        <div class="site-message">
            <span><?= get_option('store_message'); ?> </span>
        </div>
    <?php endif; ?>
    <header>
        <div class="column-50">
            <?php
            $site_logo = get_option('site_logo');
            if ($site_logo) {
                echo '<a href="/"><img src="' . esc_url($site_logo) . '" class="logo"></a>';
            }
            ?>
        </div>

        <div class="column-50">
            <?php

            $menu_header = array(
                'theme_location' => 'huvudmeny',
                'menu_id' => 'header-menu',
                'container' => 'nav',
                'container_class' => 'menu'
            );
            wp_nav_menu($menu_header);
            ?>
        </div>

        <div class="column-50 menu-icons">
            <?php
            $menu_header = array(
                'theme_location' => 'menyikoner',
                'menu_id' => 'header-menu',
                'container' => 'nav',
                'container_class' => 'menu menu-nav'
            );
            wp_nav_menu($menu_header);
            ?>
            
        </div>

    </header>