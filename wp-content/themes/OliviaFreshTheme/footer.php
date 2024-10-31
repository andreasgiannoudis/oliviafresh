<footer>
    <div class="footer-container">
        <div class="footer-left">
            <?php
                $site_logo = get_option('site_logo');
                if ($site_logo) {
                    echo '<a href="/"><img src="' . esc_url($site_logo) . '" class="logo" alt="Site Logo"></a>';
                }
            ?>
            <div class="social-media">
                <?php
                    $linkedin_url = get_option('linkedin_url');
                    $facebook_url = get_option('facebook_url');
                ?>

                <?php if ($linkedin_url): ?>
                    <a href="<?php echo esc_url($linkedin_url); ?>" target="_blank" class="social-icon">
                        <i class="fa fa-linkedin"></i>
                    </a>
                <?php endif; ?>

                <?php if ($facebook_url): ?>
                    <a href="<?php echo esc_url($facebook_url); ?>" target="_blank" class="social-icon">
                        <i class="fa fa-facebook"></i>
                    </a>
                <?php endif; ?>

            </div>
        </div>

        <div class="footer-right">
            <div class="footer-right-1">
                <h3>Bolaget</h3>
                <?php
                    $footer_menu = array(
                        'theme_location' => 'footer_meny',
                        'menu_id' => 'footer_meny',
                        'container' => 'nav',
                        'container_class' => 'menu'
                    );
                    wp_nav_menu($footer_menu);
                ?>
            </div>


            <div class="footer-right-2">
    <h3>Kontakt</h3>
    <p>Email: 
        <?php
            $email = get_option('email');
            if ($email) {
                echo '<a href="mailto:' . esc_attr($email) . '" class="email">' . esc_html($email) . '</a>';
            }
        ?>
    </p>
    
    <p>Address: 
        <?php
            $address = get_option('address');
            $city = get_option('city');
            if ($address) {
                // Format address for Google Maps link
                $formatted_address = urlencode(esc_html($address) . ' ' . esc_html($city));
                echo '<a href="https://www.google.com/maps/search/?api=1&query=' . $formatted_address . '" target="_blank" class="address">' . esc_html($address) . '<br>' . esc_html($city) . '</a>';
            }
        ?>
    </p>
</div>

        </div>
    </div>
</footer>
<div class="copyright"><?= date('Y') . " " .   get_bloginfo('name')  ?>. All rights reserved.</div>

<?php wp_footer(); ?>
</body>

</html>