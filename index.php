<?php
/**
 * File Template Index.php (Halaman Depan Statis) - VERSI FINAL RAPI
 *
 * File ini sekarang hanya bertugas sebagai "manajer" yang memanggil
 * setiap section dari folder template-parts sesuai urutan.
 */

get_header(); ?>

<main id="main-content" class="site-main">

    <?php
    // Memanggil setiap section sesuai urutan yang Anda inginkan
    get_template_part('template-parts/section', 'ticker');
    get_template_part('template-parts/section', 'front');
    get_template_part('front-page');
   
    

    ?>

</main>

<?php get_footer(); ?>