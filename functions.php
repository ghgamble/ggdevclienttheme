<?php
// Theme Support
function ggdevclienttheme_theme_setup() {
	add_theme_support('title-tag');
	add_theme_support('custom-logo');
	add_theme_support('post-thumbnails');
	add_theme_support('align-wide');
	register_nav_menus([
		'primary' => __('Primary Menu', 'ggdevclienttheme'),
	]);
}
add_action('after_setup_theme', 'ggdevclienttheme_theme_setup');

// Enqueue Styles and Scripts
function ggdevclienttheme_scripts() {
	wp_enqueue_style('ggdevclienttheme-style', get_template_directory_uri() . '/dist/style.min.css', array(), '1.0');
	wp_enqueue_script('ggdevclienttheme-js', get_template_directory_uri() . '/dist/main.min.js', array('jquery'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'ggdevclienttheme_scripts');

function ggdevclienttheme_enqueue_fontawesome() {
	wp_enqueue_style(
		'font-awesome',
		'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css',
		[],
		'6.5.0'
	);
}
add_action('wp_enqueue_scripts', 'ggdevclienttheme_enqueue_fontawesome');

// Enqueue Google Fonts and apply to frontend
function ggdevclienttheme_enqueue_google_fonts() {
	wp_enqueue_style(
		'google-fonts',
		'https://fonts.googleapis.com/css2?family=Barlow:wght@400;600;700&family=Barlow+Condensed:wght@400;600;700&family=Cormorant+Garamond:wght@400;500;700&family=Crimson+Pro:wght@400;600;700&family=Domine:wght@400;700&family=Nunito+Sans:wght@300;400;600;700&family=IBM+Plex+Sans+Condensed:wght@300;400;600;700&family=PT+Sans:wght@400;700&family=Quicksand:wght@300;400&family=Charm:wght@400;700&family=Belleza&family=Alex+Brush',
		false
	);

	$body_font = "'Domine', sans-serif";
	$heading_font = "'Roboto Slab', serif";
	$body_weight = get_theme_mod('ggdevclienttheme_font_weight', '400');
	$heading_weight = get_theme_mod('ggdevclienttheme_heading_font_weight', '700');

	$custom_css = "
		body, p, li, a, .img-label, .wp-block-button__link.wp-element-button {
			font-family: {$body_font};
			font-weight: {$body_weight};
		}

		.site-main span,
		.site-main p,
		.site-main li,
		.site-main a,
		.site-main .img-label,
		.site-main .wp-block-button__link.wp-element-button {
			font-family: {$body_font};
			font-weight: {$body_weight};
		}

		h1, h2, h3, h4, h5, h6 {
			font-family: {$heading_font};
			font-weight: {$heading_weight};
		}

		.wp-block {
			font-family: {$body_font};
		}

		.wp-block-heading {
			font-family: {$heading_font};
		}
	";
	wp_add_inline_style('ggdevclienttheme-style', $custom_css);
}
add_action('wp_enqueue_scripts', 'ggdevclienttheme_enqueue_google_fonts');

// Apply fonts in Gutenberg block editor
function ggdevclienttheme_editor_fonts() {
	wp_enqueue_style(
		'google-fonts-editor',
		'https://fonts.googleapis.com/css2?family=Barlow:wght@400;600;700&family=Barlow+Condensed:wght@400;600;700&family=Cormorant+Garamond:wght@400;500;700&family=Crimson+Pro:wght@400;600;700&family=Domine:wght@400;700&family=Nunito+Sans:wght@300;400;600;700&family=IBM+Plex+Sans+Condensed:wght@300;400;600;700&family=PT+Sans:wght@400;700&family=Quicksand:wght@300;400&family=Charm:wght@400;700&family=Belleza&family=Alex+Brush',
		false
	);

	$body_font = "'Domine', sans-serif";
	$heading_font = "'Roboto Slab', serif";

	$editor_css = "
		body, .editor-styles-wrapper {
			font-family: {$body_font} !important;
		}

		.editor-styles-wrapper h1,
		.editor-styles-wrapper h2,
		.editor-styles-wrapper h3,
		.editor-styles-wrapper h4,
		.editor-styles-wrapper h5,
		.editor-styles-wrapper h6 {
			font-family: {$heading_font} !important;
		}

		.wp-block {
			font-family: {$body_font} !important;
		}

		.wp-block-heading {
			font-family: {$heading_font} !important;
		}
	";
	wp_add_inline_style('wp-edit-blocks', $editor_css);
}
add_action('enqueue_block_editor_assets', 'ggdevclienttheme_editor_fonts');

// Enqueue Button Styles for Block Editor
add_action('enqueue_block_editor_assets', function () {
	wp_enqueue_script(
		'theme-editor-button-styles',
		get_template_directory_uri() . '/dist/button-styles.min.js',
		['wp-blocks', 'wp-dom-ready', 'wp-edit-post'],
		filemtime(get_template_directory() . '/dist/button-styles.min.js'),
		true
	);
});

// Register custom block category
function ggdevclienttheme_register_block_category($categories, $post) {
	$custom_category = array(
		array(
			'slug'  => 'ggdevclienttheme-blocks',
			'title' => __('GG Dev Blocks', 'ggdevclienttheme'),
			'icon'  => null,
		),
	);
	return array_merge($custom_category, $categories);
}
add_filter('block_categories_all', 'ggdevclienttheme_register_block_category', 10, 2);

// Include theme components
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/custom-walker.php';
require get_template_directory() . '/inc/child-theme-generator.php';

// Register Footer Widgets
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

// Allow SVG uploads for admins only
function acb_allow_svg_uploads($mimes) {
	if (current_user_can('administrator')) {
		$mimes['svg'] = 'image/svg+xml';
	}
	return $mimes;
}
add_filter('upload_mimes', 'acb_allow_svg_uploads');
