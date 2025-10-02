<?php
/**
 * Shortcode kustom untuk tema Mediman.
 * File ini berisi semua shortcode yang digunakan dalam tema.
 *
 * @package Mediman
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


/**
 * Shortcode [news_ticker]
 * Menampilkan berita berjalan dari postingan terbaru.
 *
 * @return string HTML output dari news ticker.
 */
/**
 * Shortcode untuk Typewriter News Ticker
 */
function mediman_typewriter_ticker_shortcode() {
    if ( ! get_theme_mod( 'ticker_enable', true ) ) { return ''; }

    // Mengambil data postingan
    $source = get_theme_mod( 'ticker_post_source', 'popular' );
    $args = ['post_type' => 'post', 'posts_per_page' => 5, 'ignore_sticky_posts' => 1, 'orderby' => ( 'popular' === $source ) ? 'comment_count' : 'date', 'order' => 'DESC'];
    $ticker_posts = new WP_Query($args);
    $posts_data = [];
    if ( $ticker_posts->have_posts() ) {
        while ( $ticker_posts->have_posts() ) {
            $ticker_posts->the_post();
            $posts_data[] = [ 'title' => get_the_title(), 'link'  => get_permalink() ];
        }
    }
    wp_reset_postdata();
    if (empty($posts_data)) { return ''; }
    wp_localize_script( 'mediman-typewriter', 'tickerData', $posts_data );
    
    // Mengambil semua pengaturan dari Customizer
    $text               = get_theme_mod('ticker_label_text', 'TRENDING');
    $icon               = get_theme_mod('ticker_label_icon', 'bi-lightning-fill');
    $desktop_type       = get_theme_mod('ticker_desktop_label_type', 'icon_text');
    $mobile_type        = get_theme_mod('ticker_mobile_label_type', 'icon');
    $title_block_bg     = get_theme_mod('ticker_title_bg_color', '#dc3545');
    $content_block_bg   = get_theme_mod('ticker_bg_color', '#f8f9fa');
    $content_text_color = get_theme_mod('ticker_text_color', '#212529');

    $make_label = function($type, $icon, $text) {
        $icon_html  = '<i class="bi ' . esc_attr($icon) . '"></i>';
        $text_html  = '<span class="ticker-text-label">' . esc_html($text) . '</span>';
        $label_html = '';
        switch ($type) {
            case 'text': $label_html = $text_html; break;
            case 'icon': $label_html = $icon_html; break;
            case 'icon_text': $label_html = $icon_html . ' ' . $text_html; break;
        }
        return $label_html;
    };
    $desktop_label_content = $make_label($desktop_type, $icon, $text);
    $mobile_label_content = $make_label($mobile_type, $icon, $text);

    ob_start();
    ?>
    <div class="ticker-final-wrapper" style="background-color: <?php echo esc_attr($content_block_bg); ?>;">
        
        <?php if ( ! empty( $desktop_label_content ) || ! empty( $mobile_label_content ) ) : ?>
            <div class="ticker-title-block" style="background-color: <?php echo esc_attr($title_block_bg); ?>;">
                <div class="d-none d-lg-block">
                    <?php echo $desktop_label_content; ?>
                </div>
                <div class="d-lg-none">
                    <?php echo $mobile_label_content; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="typewriter-content-block">
            <a href="#" id="typewriter-link" style="color: <?php echo esc_attr($content_text_color); ?>;">
                <span id="typewriter-text"></span><span class="cursor"></span>
            </a>
        </div>

        <?php if ( get_theme_mod( 'ticker_show_nav', false ) ) : ?>
        <div class="ticker-nav-block">
            <button id="ticker-prev" class="btn btn-sm" aria-label="Previous Article"><i class="bi bi-chevron-left"></i></button>
            <button id="ticker-next" class="btn btn-sm" aria-label="Next Article"><i class="bi bi-chevron-right"></i></button>
        </div>
        <?php endif; ?>

    </div>
    <?php
    return ob_get_clean();
}

function mediman_post_carousel_shortcode() {
    $source = get_theme_mod( 'carousel_source', 'latest' );
    $post_count = get_theme_mod( 'carousel_post_count', 4 );

    $args = [
        'post_type' => 'post', 'posts_per_page' => absint($post_count), 'ignore_sticky_posts' => 1,
        'orderby' => ('popular' === $source) ? 'comment_count' : 'date', 'order' => 'DESC',
    ];
    $carousel_query = new WP_Query($args);

    if ( ! $carousel_query->have_posts() ) return '';

    ob_start();
    $carousel_id = 'postCarousel' . rand(100, 999);
    ?>
    <section class="mediman-carousel-wrap mb-4">
        <div id="<?php echo esc_attr($carousel_id); ?>" class="carousel slide" data-bs-ride="carousel">
            
            <div class="carousel-indicators">
                <?php for ( $i = 0; $i < $carousel_query->post_count; $i++ ) : ?>
                    <button type="button" data-bs-target="#<?php echo esc_attr( $carousel_id ); ?>" data-bs-slide-to="<?php echo esc_attr( $i ); ?>" <?php echo ( 0 === $i ) ? 'class="active" aria-current="true"' : ''; ?> aria-label="Slide <?php echo esc_attr( $i + 1 ); ?>"></button>
                <?php endfor; ?>
            </div>

            <div class="carousel-inner">
                <?php $is_first = true; ?>
                <?php while ($carousel_query->have_posts()) : $carousel_query->the_post(); ?>
                    <div class="carousel-item <?php echo $is_first ? 'active' : ''; ?>">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('large', ['class' => 'd-block w-100 carousel-image', 'alt' => get_the_title()]); ?>
                        <?php else : ?>
                            <img src="<?php echo get_template_directory_uri() . '/assets/image/default.jpg'; ?>" class="d-block w-100 carousel-image" alt="Default Image">
                        <?php endif; ?>
                        
                        <div class="carousel-caption">
                            <div class="caption-content-box">
                                <?php
                                $categories = get_the_category();
                                if (!empty($categories)) : ?>
                                    <a href="<?php echo esc_url(get_category_link($categories[0]->term_id)); ?>" class="caption-category"><?php echo esc_html($categories[0]->name); ?></a>
                                <?php endif; ?>
                                <h3 class="caption-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <p class="caption-excerpt d-none d-md-block"><?php echo wp_trim_words(get_the_excerpt(), 15, '...'); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php $is_first = false; ?>
                <?php endwhile; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#<?php echo esc_attr($carousel_id); ?>" data-bs-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Previous</span></button>
            <button class="carousel-control-next" type="button" data-bs-target="#<?php echo esc_attr($carousel_id); ?>" data-bs-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Next</span></button>
        </div>
    </section>
    <?php
    wp_reset_postdata();
    return ob_get_clean();
}

add_shortcode('post_carousel', 'mediman_post_carousel_shortcode');
add_shortcode('typewriter_ticker', 'mediman_typewriter_ticker_shortcode');


