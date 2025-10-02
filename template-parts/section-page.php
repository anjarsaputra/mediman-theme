<?php
/**
 * Template Part untuk menampilkan konten dari sebuah halaman (page).
 */

if ( have_posts() ) :
    while ( have_posts() ) : the_post();
?>
    <article id="post-<?php the_ID(); ?>" <?php post_class('page-content'); ?>>
        
        <?php if ( has_post_thumbnail() ) : ?>
            <figure class="page-thumbnail mb-4">
                <?php the_post_thumbnail('full', ['class' => 'img-fluid rounded-3']); ?>
            </figure>
        <?php endif; ?>

        <div class="entry-content">
            <?php
            the_content();

            wp_link_pages([
                'before' => '<div class="page-links">' . __('Halaman:', 'mediman'),
                'after'  => '</div>',
            ]);
            ?>
        </div></article>
<?php
    endwhile;
endif;
?>