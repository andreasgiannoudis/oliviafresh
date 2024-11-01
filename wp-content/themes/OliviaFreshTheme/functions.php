<?php

if(!defined('ABSPATH')){
    exit;
}

require_once("vite.php");
require_once("hooks.php");


require_once(get_template_directory() . "/init.php");

//i am adding this to support woocommerce on my theme
function mytheme_add_woocommerce_support() {
	add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'mytheme_add_woocommerce_support' );




//FUNCTIONALITY TO ADD TO CART 
//WORKS WITH SINGLE PRODUCTS
add_action( 'wp_footer', 'single_product_ajax_add_to_cart_js_script' );
function single_product_ajax_add_to_cart_js_script() {
    ?>
    <script>
    (function($) {

    $('form.cart .single_add_to_cart_button').on('click', function(e) {
        e.preventDefault();

        var button = $(this);
        var form = button.closest('form.cart');
        var mainId = button.val();

        form.block({ message: null, overlayCSS: { background: '#fff', opacity: 0.6 } });

        var fData = form.serializeArray();
        form.find('.attribute-options button').each(function() {
            var attribute = $(this).closest('td').prev('td').find('.select-values').text().trim().replace(':', '');
            var value = $(this).data('value');
            fData.push({ name: 'attribute_' + attribute, value: value });
        });

        var selectedVariation = form.data('product_variations');
        var variation_id = 0;
        if (typeof selectedVariation === 'string' && selectedVariation.trim() !== '') {
            try {
                selectedVariation = JSON.parse(selectedVariation);
                var attributes = {};
                form.find('.attribute-options button').each(function() {
                    var attribute_name = $(this).closest('td').prev('td').find('.select-values').text().trim().replace(':', '');
                    var attribute_value = $(this).data('value');
                    attributes[attribute_name] = attribute_value;
                });
                selectedVariation.forEach(function(variation) {
                    var isMatch = true;
                    for (var attribute_name in attributes) {
                        if (attributes[attribute_name] != variation.attributes[attribute_name]) {
                            isMatch = false;
                            break;
                        }
                    }
                    if (isMatch) {
                        variation_id = variation.variation_id;
                        return false;
                    }
                });
            } catch (error) {
                console.error('Error parsing JSON data:', error);
                return; 
            }
        }

        fData.push({ name: 'variation_id', value: variation_id });

        if (mainId === '') {
            mainId = form.find('input[name="product_id"]').val();
        }

        if (typeof wc_add_to_cart_params === 'undefined')
            return false;

        $.ajax({
            type: 'POST',
            url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'custom_add_to_cart'),
            data: {
                'product_id': mainId,
                'form_data': fData
            },
            success: function(response) {
                $(document.body).trigger("wc_fragment_refresh");
                $('.woocommerce-error,.woocommerce-message').remove();
                $('input[name="quantity"]').val(1);
                $('.content-area').before(response);
                form.unblock();
            },
            error: function(error) {
                form.unblock();
            }
        });
    });

    $('.attribute-options button').on('click', function(e) {
        e.preventDefault();
        $('.attribute-options button').removeClass('selected');
        $(this).addClass('selected');
    });   
})(jQuery);






    </script>
    <?php
}

add_action( 'wc_ajax_custom_add_to_cart', 'custom_add_to_cart_handler' );
add_action( 'wc_ajax_nopriv_custom_add_to_cart', 'custom_add_to_cart_handler' );
function custom_add_to_cart_handler() {
    if( isset($_POST['product_id']) && isset($_POST['form_data']) ) {
        $product_id = $_POST['product_id'];

        $variation = $cart_item_data = $custom_data = array();
        $variation_id = 0;

        foreach ($_POST['form_data'] as $values) {
            if (strpos($values['name'], 'attribute_pa') !== false) {
                $variation[$values['name']] = $values['value'];
            } elseif ($values['name'] === 'quantity') {
                $quantity = $values['value'];
            } elseif ($values['name'] === 'variation_id') {
                $variation_id = $values['value'];
            } elseif ($values['name'] !== 'add_to_cart') {
                $custom_data[$values['name']] = esc_attr($values['value']);
            }
        }

        $product = wc_get_product( $variation_id ? $variation_id : $product_id );

        $cart_item_data = (array) apply_filters( 'woocommerce_add_cart_item_data', $cart_item_data, $product_id, $variation_id, $quantity, $custom_data );

        $cart_item_key = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation, $cart_item_data );

        if ( $cart_item_key ) {
            wc_add_notice( sprintf(
                '<a href="%s" class="button wc-forward">%s</a> %d &times; "%s" %s' ,
                wc_get_cart_url(),
                __("View cart", "woocommerce"),
                $quantity,
                $product->get_name(),
                __("has been added to your cart", "woocommerce")
            ) );
        }

        wc_print_notices();
        wp_die();
    }
}

