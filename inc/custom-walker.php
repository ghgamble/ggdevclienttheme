<?php
class GGDevClientTheme_Walker_Nav_Menu extends Walker_Nav_Menu {

    // Start Level
    function start_lvl( &$output, $depth = 0, $args = null ) {
        $output .= '<ul class="sub-menu">';
    }

    // Start Element
    function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $classes = !empty( $item->classes ) && is_array( $item->classes ) ? implode( ' ', $item->classes ) : '';
        $has_children = in_array( 'menu-item-has-children', (array) $item->classes, true );

        // Begin <li>
        $output .= '<li class="' . esc_attr( $classes ) . '"';
        if ( $has_children ) {
            $output .= ' aria-haspopup="true" aria-expanded="false"';
        }
        $output .= '>';

        // Build anchor tag
        $atts = [
            'href' => !empty( $item->url ) ? esc_url( $item->url ) : '',
        ];

        if ( !empty( $item->target ) ) {
            $atts['target'] = esc_attr( $item->target );
            $atts['rel'] = 'noopener noreferrer';
        }

        $output .= '<a';
        foreach ( $atts as $attr => $value ) {
            $output .= ' ' . $attr . '="' . $value . '"';
        }
        $output .= '>';

        // Optional: dropdown arrow in link (desktop only)
        if ( $has_children && $depth === 0 ) {
            $output .= '<span class="dropdown-arrow" aria-hidden="true">
                <svg width="12" height="8" viewBox="0 0 12 8" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 1L6 6L11 1" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>
                </svg>
            </span>';
        }

        $output .= esc_html( $item->title );
        $output .= '</a>';

        // Mobile submenu toggle button
        if ( $has_children ) {
            $output .= '<button class="submenu-toggle" aria-expanded="false" aria-label="Toggle submenu">
                <svg width="12" height="8" viewBox="0 0 12 8" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 1L6 6L11 1" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>
                </svg>
            </button>';
        }
    }

    // End Element
    function end_el( &$output, $item, $depth = 0, $args = null ) {
        $output .= "</li>\n";
    }

    // End Level
    function end_lvl( &$output, $depth = 0, $args = null ) {
        $output .= "</ul>\n";
    }
}
?>
