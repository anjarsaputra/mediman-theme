<?php
/**
 * Template untuk menampilkan halaman statis (Pages).
 * Versi ini sudah dirapikan dan dibuat responsif.
 */

get_header(); ?>

<header class="page-header-container bg-light py-5">
    <div class="container text-center">
        <?php the_title('<h1 class="page-title">', '</h1>'); ?>
        <?php 
        if ( function_exists('custom_breadcrumbs') ) {
            custom_breadcrumbs();
        }
        ?>
    </div>
</header>


<main id="main-content" class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <?php
            // Memanggil konten dari template part
            get_template_part('template-parts/section-page');
            ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>