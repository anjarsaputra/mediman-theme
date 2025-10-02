

 <?php

// === SLIDER SEKARANG DITAMPILKAN DI SINI (DI LUAR MAIN CONTAINER) ===
// Cek apakah slider diaktifkan dari Customizer
if ( get_theme_mod( 'carousel_enable', true ) ) {
    // Kita bungkus shortcode dengan div yang akan kita targetkan di CSS
    echo '<div class="full-width-carousel-container  conatiner-md">';
    echo '<div class="m-0 m-lg-3 pb-2 pb-lg-0">';
    echo do_shortcode('[typewriter_ticker]');
    echo '</div>';
    echo '</div>';
}
?>

<main  class="container-md " itemscope itemtype="http://schema.org/Blog">

    <?php
        if ( get_theme_mod( 'carousel_enable', true ) ) {
        echo do_shortcode('[post_carousel]');
    }
       get_template_part('template-parts/front/section', 'latest');

        // Panggil Blok Kategori 1
        get_template_part('template-parts/section-category-block', null, ['block_number' => 1]);

        // Panggil Blok Kategori 2
        get_template_part('template-parts/section-category-block', null, ['block_number' => 2]);

        // Panggil Blok Kategori 3
        get_template_part('template-parts/section-category-block', null, ['block_number' => 3]);
    
   ?>

</main>


