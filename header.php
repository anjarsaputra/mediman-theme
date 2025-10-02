<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php
// Cek apakah fitur diaktifkan dari Customizer
if ( get_theme_mod( 'enable_reading_progress', true ) ) : 
    // Ambil warnanya
    $progress_color = get_theme_mod('reading_progress_color', '#0d6efd');
?>
    <div id="reading-progress-bar" style="background-color: <?php echo esc_attr($progress_color); ?>;"></div>

<?php endif; ?>

<?php
// Pastikan fungsi ini ada di file inc/theme-features.php atau sejenisnya.
if (!function_exists('mediman_display_logo')) {
    function mediman_display_logo() {
        $light_logo = get_theme_mod('light_mode_logo');
        $dark_logo  = get_theme_mod('dark_mode_logo');
        
        if (!empty($light_logo)) {
            echo '<a href="' . esc_url(home_url('/')) . '" class="custom-logo-link" rel="home">';
            echo '<img id="light-mode-logo" src="' . esc_url($light_logo) . '" alt="' . esc_attr(get_bloginfo('name')) . '" class="logo logo-light">';
            if (!empty($dark_logo)) {
                echo '<img id="dark-mode-logo" src="' . esc_url($dark_logo) . '" alt="' . esc_attr(get_bloginfo('name')) . '" class="logo logo-dark">';
            }
            echo '</a>';
        } else {
            echo '<a href="' . esc_url(home_url('/')) . '" class="text-decoration-none"><h1 class="site-title m-0">' . get_bloginfo('name') . '</h1></a>';
        }
    }
}
?>

<header class="container-fluid bg-body shadow-sm">
    <div class="container-md">
        
        <div class="row align-items-center py-5">

            <div class="col-3">
                <div class="d-none d-lg-flex social-icons">
                    <?php
                    $social_icons = [
                        'facebook'  => ['url' => get_theme_mod('mediman_facebook_url'), 'icon' => 'fab fa-facebook-f'],
                        'twitter'   => ['url' => get_theme_mod('mediman_twitter_url'), 'icon' => 'fab fa-twitter'],
                        'instagram' => ['url' => get_theme_mod('mediman_instagram_url'), 'icon' => 'fab fa-instagram'],
                    ];
                    foreach ($social_icons as $key => $social) {
                        if (!empty($social['url'])) {
                            echo '<a href="' . esc_url($social['url']) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr($key) . '"><i class="' . esc_attr($social['icon']) . '"></i></a>';
                        }
                    }
                    ?>
                </div>
                <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMobileMenu" aria-controls="offcanvasMobileMenu" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            <div class="col-6 text-center">
                <?php mediman_display_logo(); ?>
            </div>

            <div class="col-3 d-flex justify-content-end align-items-center">
                
                <?php if ( get_theme_mod( 'show_dark_mode_toggle', true ) ) : ?>
                    <div class="theme-toggle-wrap me-2 me-lg-3">
                        <button id="theme-switcher" class="btn btn-sm" aria-label="Ganti mode tema">
                            <i id="theme-icon" class="bi bi-moon-stars-fill"></i>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if ( get_theme_mod( 'show_header_search', true ) ) : ?>
                    <form class="d-none d-lg-flex" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <div class="input-group">
                            <input type="search" class="form-control form-control-sm" placeholder="<?php echo esc_attr_x( 'Pencarian...', 'placeholder', 'mediman' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
                            <button class="btn btn-success btn-sm" type="submit" aria-label="pencarian"><i class="fas fa-search"></i></button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <nav class="navbar-desktop-nav navbar navbar-expand-lg border-top d-none d-lg-block">
        <div class="container-md">
            <?php
            if (has_nav_menu('main_menu') && class_exists('WP_Bootstrap_Navwalker')) {
                wp_nav_menu([
                    'theme_location'  => 'main_menu',
                    'container'       => 'div',
                    'container_class' => 'collapse navbar-collapse justify-content-center',
                    'menu_class'      => 'navbar-nav',
                    'walker'          => new WP_Bootstrap_Navwalker()
                ]);
            }
            ?>
        </div>
    </nav>
</header>

<?php if (get_theme_mod('show_demo_ads', 1) && is_active_sidebar('ad-header')) : ?>

    <div class="ad-header-area">
        <?php dynamic_sidebar('ad-header'); ?>
    </div>
<?php endif; ?>






<?php
$mobile_menu_position = get_theme_mod('mobile_menu_position', 'start');
$mobile_menu_title    = get_theme_mod('mobile_menu_title', get_bloginfo('name'));
?>
<div class="offcanvas offcanvas-<?php echo esc_attr($mobile_menu_position); ?>" tabindex="-1" id="offcanvasMobileMenu" aria-labelledby="offcanvasMobileMenuLabel">
    
    <div class="offcanvas-header border-bottom">
        <?php 
        // Memanggil fungsi untuk menampilkan logo atau judul situs
        if ( function_exists('mediman_offcanvas_header_content') ) {
            mediman_offcanvas_header_content();
        } else {
            // Fallback jika fungsi tidak ada
            echo '<h5 class="offcanvas-title" id="offcanvasMobileMenuLabel">' . esc_html(get_bloginfo('name')) . '</h5>';
        }
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body d-flex flex-column">
        
        <div class="offcanvas-main-content">
            <?php if (get_theme_mod('show_header_search', true)) : ?>
                <form class="d-flex mb-4" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                    <div class="input-group">
                        <input type="search" class="form-control" placeholder="<?php echo esc_attr_x('Pencarian…', 'placeholder', 'mediman') ?>" value="<?php echo get_search_query() ?>" name="s">
                        <button class="btn btn-success" type="submit" aria-label="pencarian"><i class="fas fa-search"></i></button>
                    </div>
                </form>
            <?php endif; ?>

            <?php
            if (has_nav_menu('main_menu')) {
                wp_nav_menu([
                    'theme_location' => 'main_menu',
                    'container'      => false,
                    'menu_class'     => 'navbar-nav mobile-nav-menu',
                ]);
            }
            ?>
        </div>

        <div class="offcanvas-footer mt-auto">
            <div class="offcanvas-social-icons text-center">
                <?php
                $social_links = [
                    'facebook'  => get_theme_mod('facebook_url'),
                    'twitter'   => get_theme_mod('twitter_url'),
                    'instagram' => get_theme_mod('instagram_url'),
                ];
                foreach ($social_links as $key => $url) {
                    if (!empty($url)) {
                        echo '<a href="' . esc_url($url) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr($key) . '"><i class="fab fa-' . esc_attr($key) . '"></i></a>';
                    }
                }
                ?>
            </div>

            <div class="offcanvas-copyright text-center mt-3">
                <?php
                $copyright_text = get_theme_mod('footer_copyright_text', '© ' . date('Y') . ' ' . get_bloginfo('name'));
                if (!empty($copyright_text)) {
                    echo '<a href="' . esc_url(home_url('/')) . '" target="_blank" rel="noopener noreferrer">';
                    echo wp_kses_post($copyright_text);
                    echo '</a>';
                }
                ?>
            </div>
        </div>



    </div>
</div>

<main id="content" class="container-md my-2">
 