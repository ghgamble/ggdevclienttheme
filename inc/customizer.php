<?php
function GGDevClientTheme_customize_register($wp_customize) {

	// === GENERAL SETTINGS ===
	$wp_customize->add_section('ggdevclienttheme_general_settings', [
		'title'    => __('General Settings', 'ggdevclienttheme'),
		'priority' => 30,
	]);

	// Header Logo
	$wp_customize->add_setting('ggdevclienttheme_header_logo', [
		'sanitize_callback' => 'esc_url_raw',
	]);
	$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'ggdevclienttheme_header_logo', [
		'label'    => __('Header Logo', 'ggdevclienttheme'),
		'section'  => 'ggdevclienttheme_general_settings',
	]));

	// Font choices
	$fonts = [
		"'Arial', sans-serif" => 'Arial',
		"'Georgia', serif" => 'Georgia',
		"'Helvetica', sans-serif" => 'Helvetica',
		"'Times New Roman', serif" => 'Times New Roman',
		"'Verdana', sans-serif" => 'Verdana',
		"'Roboto', sans-serif" => 'Roboto',
		"'Nunito Sans', sans-serif" => 'Nunito Sans',
		"'Cormorant Garamond', serif" => 'Cormorant Garamond',
		"'IBM Plex Sans Condensed', sans-serif" => 'IBM Plex Sans Condensed',
		"'PT Sans', sans-serif" => 'PT Sans',
		"'Quicksand', sans-serif" => 'Quicksand',
		"'M PLUS Rounded 1c', sans-serif" => 'M PLUS Rounded 1c',
		"'Domine', serif" => 'Domine',
		"'Fira Sans Condensed', sans-serif" => 'Fira Sans Condensed',
		"'Fraunces', serif" => 'Fraunces',
		"'Barlow Condensed', sans-serif" => 'Barlow Condensed',
		"'Barlow', sans-serif" => 'Barlow',
		"'Crimson Pro', serif" => 'Crimson Pro',
		"'Poppins', sans-serif" => 'Poppins',
		"'Great Vibes', cursive" => 'Great Vibes',
		"'Playfair Display', serif" => 'Playfair Display',
		"'Yeseva One', serif" => 'Yeseva One',
		"'Roboto Slab', serif" => 'Roboto Slab',
		"'Charm', cursive" => 'Charm',
		"'Belleza', sans-serif" => 'Belleza',
		"'Alex Brush', cursive" => 'Alex Brush'
	];

	$wp_customize->add_setting('ggdevclienttheme_font_family', [
		'default' => "'Domine', sans-serif",
		'sanitize_callback' => 'sanitize_text_field',
	]);
	$wp_customize->add_control('ggdevclienttheme_font_family', [
		'label'   => __('Body Font Family', 'ggdevclienttheme'),
		'section' => 'ggdevclienttheme_general_settings',
		'type'    => 'select',
		'choices' => $fonts,
	]);

	$wp_customize->add_setting('ggdevclienttheme_heading_font_family', [
		'default' => "'Roboto Slab', sans-serif",
		'sanitize_callback' => 'sanitize_text_field',
	]);
	$wp_customize->add_control('ggdevclienttheme_heading_font_family', [
		'label'   => __('Heading Font Family', 'ggdevclienttheme'),
		'section' => 'ggdevclienttheme_general_settings',
		'type'    => 'select',
		'choices' => $fonts,
	]);

	$wp_customize->add_setting('ggdevclienttheme_font_weight', [
		'default' => '400',
		'sanitize_callback' => 'absint',
	]);
	$wp_customize->add_control('ggdevclienttheme_font_weight', [
		'label'   => __('Body Font Weight', 'ggdevclienttheme'),
		'section' => 'ggdevclienttheme_general_settings',
		'type'    => 'number',
	]);

	$wp_customize->add_setting('ggdevclienttheme_heading_font_weight', [
		'default' => '700',
		'sanitize_callback' => 'absint',
	]);
	$wp_customize->add_control('ggdevclienttheme_heading_font_weight', [
		'label'   => __('Heading Font Weight', 'ggdevclienttheme'),
		'section' => 'ggdevclienttheme_general_settings',
		'type'    => 'number',
	]);

	$wp_customize->add_setting('ggdevclienttheme_layout_width', [
		'default' => '1100px',
		'sanitize_callback' => 'sanitize_text_field',
	]);
	$wp_customize->add_control('ggdevclienttheme_layout_width', [
		'label' => __('Max Content Width (alignwide)', 'ggdevclienttheme'),
		'section' => 'ggdevclienttheme_general_settings',
		'type' => 'text',
		'description' => __('E.g., 960px or 90%', 'ggdevclienttheme'),
	]);

	$wp_customize->add_setting('ggdevclienttheme_dark_mode', [
		'default' => false,
		'sanitize_callback' => 'rest_sanitize_boolean',
	]);
	$wp_customize->add_control('ggdevclienttheme_dark_mode', [
		'label' => __('Enable Dark Mode', 'ggdevclienttheme'),
		'section' => 'ggdevclienttheme_general_settings',
		'type' => 'checkbox',
	]);

	// === COLOR SETTINGS ===
	$wp_customize->add_section('ggdevclienttheme_color_settings', [
		'title'    => __('Color Settings', 'ggdevclienttheme'),
		'priority' => 35,
	]);

	$colors = [
		'ggdevclienttheme_header_bg' => ['#222222', __('Header Background Color', 'ggdevclienttheme')],
		'ggdevclienttheme_header_font_color' => ['#20ddae', __('Header Font Color', 'ggdevclienttheme')],
		'ggdevclienttheme_footer_bg' => ['#222222', __('Footer Background Color', 'ggdevclienttheme')],
		'ggdevclienttheme_link_hover_color' => ['#1bbd97', __('Link Hover Color', 'ggdevclienttheme')],
		'ggdevclienttheme_body_bg_color' => ['#ffffff', __('Body Background Color', 'ggdevclienttheme')],
		'ggdevclienttheme_contact_header_color' => ['#282b35', __('Contact Form Header Color', 'ggdevclienttheme')],
		'ggdevclienttheme_contact_button_color' => ['#20ddae', __('Contact Form Button Color', 'ggdevclienttheme')],
		'ggdevclienttheme_contact_button_hover_color' => ['#1ab89a', __('Contact Form Button Hover Color', 'ggdevclienttheme')],
		'ggdevclienttheme_body_link_color' => ['#20ddae', __('Body Link Color', 'ggdevclienttheme')],
		'ggdevclienttheme_body_link_hover_color' => ['#1bbd97', __('Body Link Hover Color', 'ggdevclienttheme')],
		'ggdevclienttheme_skip_top_bg_color' => ['#20ddae', __('Skip to Top Background Color', 'ggdevclienttheme')],
		'ggdevclienttheme_skip_top_bg_hover_color' => ['#1cc89c', __('Skip to Top Background Hover Color', 'ggdevclienttheme')],
		'ggdevclienttheme_skip_top_font_color' => ['#ffffff', __('Skip to Top Font Color', 'ggdevclienttheme')],
		'ggdevclienttheme_skip_top_font_hover_color' => ['#282b35', __('Skip to Top Font Hover Color', 'ggdevclienttheme')],
		'ggdevclienttheme_footer_link_color' => ['#20ddae', __('Footer Link Color', 'ggdevclienttheme')],
		'ggdevclienttheme_footer_link_hover_color' => ['#1bbd97', __('Footer Link Color', 'ggdevclienttheme')]
	];

	foreach ($colors as $id => [$default, $label]) {
		$wp_customize->add_setting($id, [
			'default'           => $default,
			'sanitize_callback' => 'sanitize_hex_color',
		]);
		$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $id, [
			'label'   => $label,
			'section' => 'ggdevclienttheme_color_settings',
		]));
	}

	// Hamburger Icon Color
	$wp_customize->add_setting('ggdevclienttheme_hamburger_icon_color', [
		'default' => '#20ddae',
		'sanitize_callback' => 'sanitize_hex_color',
	]);
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'ggdevclienttheme_hamburger_icon_color', [
		'label' => __('Hamburger Icon Color', 'ggdevclienttheme'),
		'section' => 'ggdevclienttheme_color_settings',
	]));

	// === ACCESSIBILITY SETTINGS ===
	$wp_customize->add_section('ggdevclienttheme_accessibility', [
		'title' => __('Accessibility Settings', 'ggdevclienttheme'),
		'priority' => 40,
	]);

	$wp_customize->add_setting('ggdevclienttheme_font_scale', [
		'default' => '1',
		'sanitize_callback' => 'floatval',
	]);
	$wp_customize->add_control('ggdevclienttheme_font_scale', [
		'label' => __('Font Size Scale Multiplier', 'ggdevclienttheme'),
		'section' => 'ggdevclienttheme_accessibility',
		'type' => 'number',
		'input_attrs' => [
			'step' => 0.1,
			'min' => 0.8,
			'max' => 2.0,
		],
		'description' => __('1 = default size, 1.2 = 20% larger fonts.', 'ggdevclienttheme'),
	]);

	$wp_customize->add_setting('ggdevclienttheme_reduce_motion', [
		'default' => false,
		'sanitize_callback' => 'rest_sanitize_boolean',
	]);
	$wp_customize->add_control('ggdevclienttheme_reduce_motion', [
		'label' => __('Reduce Motion / Disable Animations', 'ggdevclienttheme'),
		'section' => 'ggdevclienttheme_accessibility',
		'type' => 'checkbox',
	]);
}
add_action('customize_register', 'ggdevclienttheme_customize_register');

/**
 * Output the Customizer styles in the <head>
 */
function ggdevclienttheme_customizer_styles() {
	$body_font = get_theme_mod('ggdevclienttheme_font_family', "'Roboto', sans-serif");
	$heading_font = get_theme_mod('ggdevclienttheme_heading_font_family', "'Arial', sans-serif");
	$body_weight = get_theme_mod('ggdevclienttheme_font_weight', 400);
	$heading_weight = get_theme_mod('ggdevclienttheme_heading_font_weight', 700);
	$font_scale = get_theme_mod('ggdevclienttheme_font_scale', 1);
?>
<style type="text/css">
	:root {
		font-size: calc(1rem * <?php echo $font_scale; ?>);
	}

	body {
		background-color: <?php echo get_theme_mod('ggdevclienttheme_body_bg_color', '#ffffff'); ?>;
		font-family: <?php echo $body_font; ?> !important;
		font-weight: <?php echo $body_weight; ?>;
	}

	/* Headings */
	h1, h2, h3, h4, h5, h6 {
		font-family: <?php echo $heading_font; ?> !important;
		font-weight: <?php echo $heading_weight; ?>;
	}

	.wp-block-heading {
		font-family: <?php echo $heading_font; ?> !important;
	}

	.site p,
	.site li,
	.site a,
	.site span,
	.site div,
	.site input,
	.site textarea,
	.site select,
	.site button,
	.site .wpcf7-form-control,
	.site .wp-block,
	.site .wp-block-paragraph,
	.site .wp-block-button__link.wp-element-button {
		font-family: <?php echo $body_font; ?> !important;
	}

	main#primary.site-main a {
		color: <?php echo get_theme_mod('ggdevclienttheme_body_link_color', '#20ddae'); ?>;
	}

	main#primary.site-main a:hover, main#primary.site-main a:focus, main#primary.site-main a:active, main#primary.site-main a:visited {
		color: <?php echo get_theme_mod('ggdevclienttheme_body_link_hover_color', '#1bbd97'); ?>;
	}

	ul#menu-main-menu li a,
	div#mobile-primary-menu li a {
		font-family: <?php echo $body_font; ?> !important;
	}

	.site-footer,
	.site-footer p,
	.site-footer li,
	.site-footer a,
	.site-footer h1,
	.site-footer h2,
	.site-footer h3,
	.site-footer h4,
	.site-footer h5,
	.site-footer h6 {
		font-family: <?php echo $body_font; ?> !important;
	}

	header.site-header, div#mobile-primary-menu {
		background-color: <?php echo get_theme_mod('ggdevclienttheme_header_bg', '#282b35'); ?>;
	}

	header.site-header .header-nav .menu li a, header.site-header ul#menu-main-menu li a, header.site-header div#mobile-primary-menu li a {
		color: <?php echo get_theme_mod('ggdevclienttheme_header_font_color', '#20ddae'); ?>;
	}

	header.site-header .header-nav .menu li a:hover, header.site-header ul#menu-main-menu li a:hover, header.site-header div#mobile-primary-menu li a:hover {
		color: <?php echo get_theme_mod('ggdevclienttheme_header_font_color', '#1bbd97'); ?>;
	}

	ul#menu-main-menu li a, div#mobile-primary-menu li a {
		color: <?php echo get_theme_mod('ggdevclienttheme_header_font_color', '#ffffff'); ?>;
	}

	.site-footer {
		background-color: <?php echo get_theme_mod('ggdevclienttheme_footer_bg', '#282b35'); ?>;
	}

	a:hover {
		color: <?php echo get_theme_mod('ggdevclienttheme_link_hover_color', '#03678e'); ?>;
	}

	.o-container {
		max-width: <?php echo get_theme_mod('ggdevclienttheme_layout_width', '1100px'); ?>;
	}

	.menu-toggle .hamburger-icon rect {
		fill: <?php echo get_theme_mod('ggdevclienttheme_hamburger_icon_color', '#20ddae'); ?>;
	}

	div#career-block .apploi-drop-down select#job-title-filter {
		background-color: <?php echo get_theme_mod('ggdevclienttheme_career_accent_color', '#03678e'); ?> !important;
		border: 1px solid <?php echo get_theme_mod('ggdevclienttheme_career_accent_color', '#03678e'); ?> !important;
	}

	a.job-link {
		color: <?php echo get_theme_mod('ggdevclienttheme_career_accent_color', '#03678e'); ?> !important;
	}

	.wpcf7 h3 {
		color: <?php echo get_theme_mod('ggdevclienttheme_contact_header_color', '#282b35'); ?>;
	}

	.wpcf7 input[type=submit] {
		background: <?php echo get_theme_mod('ggdevclienttheme_contact_button_color', '#20ddae'); ?>;
	}

	.wpcf7 input[type=submit]:hover {
		background: <?php echo get_theme_mod('ggdevclienttheme_contact_button_hover_color', '#1ab89a'); ?>;
	}

	.skip-to-content {
		background: <?php echo get_theme_mod('ggdevclienttheme_skip_top_bg_color', '#20ddae'); ?>;
		color: <?php echo get_theme_mod('ggdevclienttheme_skip_top_font_color', '#ffffff'); ?>;
	}

	.skip-to-content:hover {
		background: <?php echo get_theme_mod('ggdevclienttheme_skip_top_bg_color', '#1ab89a'); ?>;
		color: <?php echo get_theme_mod('ggdevclienttheme_skip_top_font_hover_color', '#282b35'); ?>;
	}

	.footer-social .footer-icons a {
		color: <?php echo get_theme_mod('ggdevclienttheme_footer_link_color', '#20ddae'); ?>;
	}

	.footer-social .footer-icons a:hover {
		color: <?php echo get_theme_mod('ggdevclienttheme_footer_link_color', '#1ab89a'); ?>;
	}

	<?php if (get_theme_mod('ggdevclienttheme_reduce_motion')) : ?>
	html {
		scroll-behavior: auto !important;
	}
	*, *::before, *::after {
		animation: none !important;
		transition: none !important;
	}
	<?php endif; ?>

	<?php if (get_theme_mod('ggdevclienttheme_dark_mode')) : ?>
	body { background: #121212; color: #e0e0e0; }
	.site-header, .site-footer { background: #1f1f1f; }
	a { color: #bb86fc; }
	a:hover { color: #3700b3; }
	<?php endif; ?>
</style>
<?php
}
add_action('wp_head', 'ggdevclienttheme_customizer_styles');
