<?php

function best_sellers_carousel_shortcode()
{
    ob_start();

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 10,
        'product_cat' => 'best-sellers',
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) : ?>
        <div class="swiper-container best-sellers-carousel">
            <div class="swiper-wrapper">
                <?php while ($query->have_posts()) : $query->the_post(); ?>
                    <div class="swiper-slide product-slide">
                        <a href="<?php the_permalink(); ?>" class="product-link">
                            <div class="product-image">
                                <?php if (has_post_thumbnail()) {
                                    the_post_thumbnail('medium');
                                } ?>
                            </div>
                            <div class="product-info">
                                <h3 class="product-title"><?php the_title(); ?></h3>
                                <span class="product-price"><?php woocommerce_template_loop_price(); ?></span>
                            </div>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
<?php endif;

    wp_reset_postdata();

    return ob_get_clean();
}
