<?php get_header(); ?>

<!-- CONTENT -->
<main class="content">
    <?= the_content(); ?>
    <?php 
        do_action("mytheme_page_content_loaded");
    ?> 
</main>


<?php get_footer(); ?>
