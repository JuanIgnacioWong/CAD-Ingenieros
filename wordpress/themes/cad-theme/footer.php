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

            <address class="cad-footer__address">
                <span>AV. SANTA MARIA 2450 PROVIDENCIA</span>
                <span>SANTIAGO - CHILE</span>
                <a href="tel:+56224644700">+56 2 2464 4700</a>
            </address>

            <div class="cad-footer__social">
                <a href="https://www.instagram.com/somoscad?igshid=jwy8h5uamg3f" target="_blank" rel="noopener noreferrer" aria-label="Instagram">Instagram</a>
                <a href="https://www.linkedin.com/company/cadsa/" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">LinkedIn</a>
                <a href="https://www.youtube.com/channel/UC_G_L0F8-RDHMJYtKyM0uAw" target="_blank" rel="noopener noreferrer" aria-label="YouTube">YouTube</a>
            </div>
        </div>
    </footer>
</div>
</div>

<?php wp_footer(); ?>
</body>
</html>
