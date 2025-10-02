<?php get_header(); ?>



<main id="main-content" class="site-main">             
  <header class="page-header">

                <?php
                  // Menampilkan deskripsi kategori jika ada
                  the_archive_description( '<div class="archive-description">', '</div>' );
                  ?>
                  <div>
                              
                  <div class="border-bottom  d-flex justify-content-between">
                  <div class="border-bottom border-2 border-dark"  >
                    <div class="judularsip">
                    <h2 class="h2 fw-bold fs-3"><?php single_cat_title(); ?></h2> 
                    </div>
                  </div>

                
            </header>
   
          <section class=" my-4">

              <?php get_template_part( 'template-parts/section','archive' );?>
              
          </section>
</div>
  

</main>

<?php get_footer(); ?>
