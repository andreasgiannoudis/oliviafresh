<?php 

function cosmetics_hero_shortcode($atts) {
    // Extract the attributes and set defaults
    $atts = shortcode_atts(array(
        'img' => '',  // Image ID for background
        'product_title' => '', // Product title to search
    ), $atts, 'cosmetics_hero');

    // Get the image URL from the ID provided
    $background_image = '';
    if (!empty($atts['img'])) {
        $background_image = wp_get_attachment_url($atts['img']);
    }

    // Query for the product based on the provided title
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 1,
        'title' => $atts['product_title'], // Search for product by title
    );

    // Modify the query to search for product by title
    add_filter('posts_search', function($search, $wp_query) {
        global $wpdb;

        // Only modify search for this specific query
        if ($wp_query->get('post_type') === 'product' && $title = $wp_query->get('title')) {
            $search = $wpdb->prepare(" AND {$wpdb->posts}.post_title LIKE %s", '%' . $wpdb->esc_like($title) . '%');
        }
        return $search;
    }, 10, 2);

    $products = new WP_Query($args);
    
    ob_start();
    ?>
    <section id="cosmetics-hero" class="cosmetics-hero" style="background-image: url('<?php echo esc_url($background_image); ?>');">
        <div class="text-content">
            <h1>
               <span class="highlight">Lyxig</span> skönhet <span class="highlight">alla</span> förtjänar
            </h1>
            <p>Förhöj din skönhetsrutin: Unveil Your Radiant Glow Today!</p>
            <a href="/shop" class="shop-now">Köp nu &raquo;</a>
        </div>

        <div class="product-display">
            <?php if ($products->have_posts()) : ?>
                <?php while ($products->have_posts()) : $products->the_post(); 
                    $product = wc_get_product(get_the_ID()); // Get the product object
                ?>
                    <div class="product-item">
                        <a href="<?php the_permalink(); ?>">
                            <?php
                                // Display the product thumbnail
                                echo woocommerce_get_product_thumbnail();
                            ?>
                            <h2><?php the_title(); ?></h2>
                            <span class="price"><?php echo wp_kses_post($product->get_price_html()); ?></span>
                        </a>
                    </div>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <p>Produkt inte hittad.</p>
            <?php endif; ?>
        </div>
    </section>
    <?php
    remove_filter('posts_search', 'custom_product_search', 10, 2); // Clean up
    return ob_get_clean();
}
