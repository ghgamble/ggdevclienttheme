<?php
/**
 * Theme setup
 */
function ggdevclienttheme_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('custom-logo');
    add_theme_support('post-thumbnails');
    add_theme_support('align-wide');
    add_theme_support('editor-styles'); // allow editor styles to load
    register_nav_menus([
        'primary' => __('Primary Menu', 'ggdevclienttheme'),
    ]);
}
add_action('after_setup_theme', 'ggdevclienttheme_theme_setup');

/**
 * Main styles & scripts
 */
function ggdevclienttheme_scripts() {
    // Main stylesheet and JS (keep your existing handles/paths)
    wp_enqueue_style(
        'ggdevclienttheme-style',
        get_template_directory_uri() . '/dist/style.min.css',
        [],
        '1.0'
    );

    wp_enqueue_script(
        'ggdevclienttheme-js',
        get_template_directory_uri() . '/dist/main.min.js',
        ['jquery'],
        '1.0',
        true
    );

    // Google Fonts (frontend) — no inline CSS, just the font files
    wp_enqueue_style(
        'ggdevclienttheme-google-fonts',
        ggdevclienttheme_google_fonts_url(),
        [],
        null
    );
}
add_action('wp_enqueue_scripts', 'ggdevclienttheme_scripts', 20);

/**
 * Font Awesome
 */
function ggdevclienttheme_enqueue_fontawesome() {
    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css',
        [],
        '6.5.0'
    );
}
add_action('wp_enqueue_scripts', 'ggdevclienttheme_enqueue_fontawesome');

/**
 * Build a single Google Fonts URL including all families exposed in the Customizer
 */
function ggdevclienttheme_google_fonts_url() {
    $families = [
        // From your Customizer choices:
        'Barlow:wght@400;600;700',
        'Barlow+Condensed:wght@400;600;700',
        'Cormorant+Garamond:wght@400;500;700',
        'Crimson+Pro:wght@400;600;700',
        'Domine:wght@400;700',
        'Nunito+Sans:wght@300;400;600;700',
        'IBM+Plex+Sans+Condensed:wght@300;400;600;700',
        'PT+Sans:wght@400;700',
        'Quicksand:wght@300;400',
        'M+PLUS+Rounded+1c:wght@300;400;600;700',
        'Fira+Sans+Condensed:wght@300;400;600;700',
        'Fraunces:wght@400;700',
        'Poppins:wght@300;400;600;700',
        'Great+Vibes',
        'Playfair+Display:wght@400;700',
        'Yeseva+One',
        'Roboto:wght@400;700',
        'Roboto+Slab:wght@400;700',
        'Charm:wght@400;700',
        'Belleza',
        'Alex+Brush'
    ];

    $query = implode('&', array_map(static fn($f) => 'family=' . $f, $families));
    return 'https://fonts.googleapis.com/css2?' . $query . '&display=swap';
}

/**
 * Load the same Google fonts inside the block editor
 */
add_action('enqueue_block_editor_assets', function () {
    wp_enqueue_style(
        'ggdevclienttheme-google-fonts-editor',
        ggdevclienttheme_google_fonts_url(),
        [],
        null
    );

    // If you have editor-specific CSS, you can also load it:
    // add_editor_style('dist/editor.css');
}, 20);

/**
 * Editor button styles (kept)
 */
add_action('enqueue_block_editor_assets', function () {
    wp_enqueue_script(
        'theme-editor-button-styles',
        get_template_directory_uri() . '/dist/button-styles.min.js',
        ['wp-blocks', 'wp-dom-ready', 'wp-edit-post'],
        filemtime(get_template_directory() . '/dist/button-styles.min.js'),
        true
    );
});

/**
 * Custom block category (kept)
 */
function ggdevclienttheme_register_block_category($categories, $post) {
    $custom_category = [
        [
            'slug'  => 'ggdevclienttheme-blocks',
            'title' => __('GG Dev Blocks', 'ggdevclienttheme'),
            'icon'  => null,
        ],
    ];
    return array_merge($custom_category, $categories);
}
add_filter('block_categories_all', 'ggdevclienttheme_register_block_category', 10, 2);

/**
 * Include theme components (kept)
 */
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/custom-walker.php';
require get_template_directory() . '/inc/child-theme-generator.php';
require get_template_directory() . '/inc/role-owner.php';

/**
 * Footer widgets (kept)
 */
function ggdevclienttheme_register_footer_widgets() {
    register_sidebar([
        'name'          => __('Footer Social Info', 'ggdevclienttheme'),
        'id'            => 'footer-social-info',
        'before_widget' => '<div class="footer-social-links-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="footer-social-info-title">',
        'after_title'   => '</h3>',
    ]);
}
add_action('widgets_init', 'ggdevclienttheme_register_footer_widgets');

/**
 * Allow SVG uploads for admins only (kept)
 */
function acb_allow_svg_uploads($mimes) {
    if (current_user_can('administrator')) {
        $mimes['svg'] = 'image/svg+xml';
    }
    return $mimes;
}
add_filter('upload_mimes', 'acb_allow_svg_uploads');
