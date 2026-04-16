    <?php $footer_contact = cad_theme_get_footer_contact_data(); ?>
    <footer class="cad-footer" id="footer-contactanos">
        <div class="cad-footer__inner">
            <nav class="cad-footer__nav" aria-label="<?php esc_attr_e('Menu de pie de pagina', 'cad-theme'); ?>">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'footer',
                        'container'      => false,
                        'menu_class'     => 'cad-footer-links',
                        'fallback_cb'    => 'cad_theme_render_default_footer_menu',
                    )
                );
                ?>
            </nav>

            <?php if (!empty($footer_contact['address_lines']) || !empty($footer_contact['phone_label'])) : ?>
                <address class="cad-footer__address">
                    <?php foreach ($footer_contact['address_lines'] as $line) : ?>
                        <span><?php echo esc_html($line); ?></span>
                    <?php endforeach; ?>

                    <?php if (!empty($footer_contact['phone_label'])) : ?>
                        <?php if (!empty($footer_contact['phone_url'])) : ?>
                            <a href="<?php echo esc_url($footer_contact['phone_url']); ?>"><?php echo esc_html($footer_contact['phone_label']); ?></a>
                        <?php else : ?>
                            <span><?php echo esc_html($footer_contact['phone_label']); ?></span>
                        <?php endif; ?>
                    <?php endif; ?>
                </address>
            <?php endif; ?>

            <?php if (!empty($footer_contact['social_links'])) : ?>
                <div class="cad-footer__social">
                    <?php foreach ($footer_contact['social_links'] as $social_link) : ?>
                        <?php
                        $label = isset($social_link['label']) ? (string) $social_link['label'] : '';
                        $url = isset($social_link['url']) ? (string) $social_link['url'] : '';
                        if ('' === $label || '' === $url) {
                            continue;
                        }
                        ?>
                        <a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr($label); ?>"><?php echo esc_html($label); ?></a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </footer>
</div>
</div>

<?php wp_footer(); ?>
</body>
</html>
