</main>

<footer id="colophon" class="site-footer-wrapper mt-4">
    <div class="container-md">
        <div class="site-footer-inner text-center">

        <?php
// Cek apakah ada widget aktif di salah satu area footer
if (is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3')) :
?>
    <div class="footer-widgets-area">
        <div class="container">
            <div class="footer-widgets-columns">
                <div class="footer-widget-column">
                    <?php dynamic_sidebar('footer-1'); ?>
                </div>
                <div class="footer-widget-column">
                    <?php dynamic_sidebar('footer-2'); ?>
                </div>
                <div class="footer-widget-column">
                    <?php dynamic_sidebar('footer-3'); ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<style>
    .footer-widgets-columns {
        display: flex;
        gap: 30px;
    }
    .footer-widget-column {
        flex: 1;
    }
</style>
            <?php
            // Ambil teks copyright dari Customizer
            $copyright_text = get_theme_mod('footer_copyright_text', '© ' . date('Y') . ' ' . get_bloginfo('name') . '. All Rights Reserved.');
            
            // Tampilkan teksnya
            echo wp_kses_post($copyright_text);
            ?>
        </div>
    </div>
</footer>

<?php
// Tampilkan tombol Scroll to Top jika diaktifkan dari Customizer
if ( get_theme_mod( 'enable_scroll_to_top', true ) ) :
    // Ambil kelas ikon yang dipilih dari Customizer
    $scroll_icon_class = get_theme_mod( 'scroll_top_icon_class', 'bi-arrow-up' );
?>
    <a href="#" id="scrollToTopBtn" class="scroll-to-top-button" aria-label="Kembali ke atas">
        <i class="bi <?php echo esc_attr( $scroll_icon_class ); ?>"></i>
    </a>
<?php endif; ?>

</div><?php wp_footer(); ?>


<?php if ( get_theme_mod( 'enable_animated_cursor', false ) ) : ?>
<script>
    // Menambahkan kelas ke body untuk menyembunyikan kursor asli via CSS
    document.body.classList.add('custom-cursor-active');
</script>
<?php endif; ?>
</body>
</html>