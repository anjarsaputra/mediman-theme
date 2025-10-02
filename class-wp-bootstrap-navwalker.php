<?php
/**
 * WP Bootstrap Navwalker v5.3.2
 *
 * A custom WordPress nav walker class to implement the Bootstrap 5 navigation style in a custom theme using the WordPress built in menu manager.
 *
 * @package WP-Bootstrap-Navwalker
 */

// Check if Class Exists.
if ( ! class_exists( 'WP_Bootstrap_Navwalker' ) ) {
    /**
     * WP_Bootstrap_Navwalker class.
     *
     * @extends Walker_Nav_Menu
     */
    class WP_Bootstrap_Navwalker extends Walker_Nav_Menu {
        
        public function start_lvl( &$output, $depth = 0, $args = null ) {
            $indent = str_repeat( "\t", $depth );
            $output .= "\n$indent<ul role=\"menu\" class=\"dropdown-menu\">\n";
        }

        public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
            $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

            $li_attributes = '';
            $class_names = $value = '';

            $classes = empty( $item->classes ) ? array() : (array) $item->classes;
            // PERBAIKAN: Mengecek sub-menu melalui class bawaan WordPress, bukan $args.
            $has_children = in_array( 'menu-item-has-children', $classes, true );

            $classes[] = 'nav-item';
            $classes[] = 'menu-item-' . $item->ID;
            if ( $has_children ) {
                $classes[] = 'dropdown';
            }
            if ( in_array( 'current-menu-item', $classes, true ) || in_array( 'current-menu-parent', $classes, true ) ) {
                $classes[] = 'active';
            }

            $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
            $class_names = ' class="' . esc_attr( $class_names ) . '"';

            $id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args );
            $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

            $output .= $indent . '<li' . $id . $value . $class_names . $li_attributes . '>';

            $atts = array();
            $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
            $atts['target'] = ! empty( $item->target )     ? $item->target     : '';
            $atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';

            // If item has_children add dropdown attributes.
            if ( $has_children && 0 === $depth ) {
                $atts['href']          = '#';
                $atts['data-bs-toggle'] = 'dropdown';
                $atts['aria-expanded'] = 'false';
                $atts['class']         = 'nav-link dropdown-toggle';
            } else {
                $atts['href'] = ! empty( $item->url ) ? $item->url : '#';
                if ( $depth > 0 ) {
                    $atts['class'] = 'dropdown-item';
                } else {
                    $atts['class'] = 'nav-link';
                }
            }
            
            $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

            $attributes = '';
            foreach ( $atts as $attr => $value ) {
                if ( ! empty( $value ) ) {
                    $value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                    $attributes .= ' ' . $attr . '="' . $value . '"';
                }
            }
            
            $title = apply_filters( 'the_title', $item->title, $item->ID );
            $item_output  = $args->before . '<a' . $attributes . '>';
            $item_output .= $args->link_before . $title . $args->link_after;
            $item_output .= '</a>' . $args->after;

            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
        }
    }
}