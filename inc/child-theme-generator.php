<?php
// inc/child-theme-generator.php
if (!defined('ABSPATH')) { exit; }

/**
 * Simple loader notice so you know this file is actually included.
 */
add_action('admin_notices', function () {
	if (!current_user_can('edit_theme_options')) { return; }
	echo '<div class="notice notice-info is-dismissible"><p>GG Dev: Child Theme Generator loaded.</p></div>';
});

/**
 * Admin page under Appearance.
 * Using 'edit_theme_options' is reliable for Admins on single-site and multisite.
 */
add_action('admin_menu', function () {
	add_theme_page(
		__('Generate Child Theme', 'ggdevclienttheme'),
		__('Generate Child Theme', 'ggdevclienttheme'),
		'edit_theme_options',
		'ggdevclienttheme-generate-child',
		'ggdevclienttheme_child_theme_page_cb'
	);
});

/**
 * Page callback with button to generate (and optionally activate) the child theme.
 */
function ggdevclienttheme_child_theme_page_cb() {
	if (!current_user_can('edit_theme_options')) {
		wp_die(__('You do not have permission to do this.', 'ggdevclienttheme'));
	}

	$created = false;
	$activated = false;
	$error = '';

	if (isset($_POST['ggdevclienttheme_generate_child']) && check_admin_referer('ggdevclienttheme_generate_child_nonce')) {
		$activate = !empty($_POST['activate_child']);
		$result = ggdevclienttheme_generate_child_theme($activate);
		if (is_wp_error($result)) {
			$error = $result->get_error_message();
		} else {
			$created = true;
			$activated = (bool) $result['activated'];
		}
	}

	?>
	<div class="wrap">
		<h1><?php esc_html_e('Generate Child Theme', 'ggdevclienttheme'); ?></h1>

		<?php if ($error): ?>
			<div class="notice notice-error"><p><?php echo esc_html($error); ?></p></div>
		<?php elseif ($created && $activated): ?>
			<div class="notice notice-success"><p><?php esc_html_e('Child theme generated and activated.', 'ggdevclienttheme'); ?></p></div>
		<?php elseif ($created): ?>
			<div class="notice notice-success"><p><?php esc_html_e('Child theme generated. You can activate it now in Appearance → Themes.', 'ggdevclienttheme'); ?></p></div>
		<?php endif; ?>

		<p><?php esc_html_e('Click the button below to create a child theme for this parent. It will add a minimal style.css and functions.php, enqueue the child stylesheet after the parent, and (optionally) copy the screenshot.', 'ggdevclienttheme'); ?></p>

		<form method="post">
			<?php wp_nonce_field('ggdevclienttheme_generate_child_nonce'); ?>
			<p>
				<label>
					<input type="checkbox" name="activate_child" value="1" />
					<?php esc_html_e('Activate child theme after creation', 'ggdevclienttheme'); ?>
				</label>
			</p>
			<?php submit_button(__('Generate Child Theme', 'ggdevclienttheme'), 'primary', 'ggdevclienttheme_generate_child'); ?>
		</form>

		<hr />
		<p><strong><?php esc_html_e('No menu? Use the direct URL trigger once:', 'ggdevclienttheme'); ?></strong></p>
		<p>
			<code><?php echo esc_html( admin_url('themes.php?ggdev_makechild=1') ); ?></code>
		</p>
	</div>
	<?php
}

/**
 * Fallback: direct URL trigger to create the child theme without using the UI.
 * Visit /wp-admin/themes.php?ggdev_makechild=1 (admins only).
 */
add_action('admin_init', function () {
	if (!is_admin() || !current_user_can('edit_theme_options')) { return; }
	if (!isset($_GET['ggdev_makechild']) || $_GET['ggdev_makechild'] !== '1') { return; }

	$result = ggdevclienttheme_generate_child_theme(false);
	if (is_wp_error($result)) {
		wp_die('Child theme error: ' . esc_html($result->get_error_message()));
	} else {
		wp_safe_redirect( admin_url('themes.php?ggdev_child_done=1') );
		exit;
	}
});

add_action('admin_notices', function () {
	if (!current_user_can('edit_theme_options')) { return; }
	if (!isset($_GET['ggdev_child_done'])) { return; }
	echo '<div class="notice notice-success is-dismissible"><p>Child theme generated via direct URL trigger.</p></div>';
});

/**
 * Core generator.
 */
function ggdevclienttheme_generate_child_theme($activate = false) {
	$parent_slug  = get_template(); // parent dir, e.g. ggdevclienttheme
	$parent_theme = wp_get_theme($parent_slug);
	$parent_name  = $parent_theme->get('Name') ?: $parent_slug;

	$child_slug   = sanitize_title($parent_slug . '-child');
	$child_name   = $parent_name . ' Child';

	$themes_dir   = get_theme_root();
	$child_path   = trailingslashit($themes_dir) . $child_slug;

	// Already exists?
	if (file_exists($child_path)) {
		return new WP_Error('exists', sprintf(__('Folder already exists: %s', 'ggdevclienttheme'), $child_path));
	}

	// Create directory
	if (!wp_mkdir_p($child_path)) {
		return new WP_Error('mkdir_failed', __('Failed to create child theme directory.', 'ggdevclienttheme'));
	}

	// style.css
	$style_css = "/*
Theme Name: {$child_name}
Theme URI: https://gg-dev.co/
Description: Child theme for {$parent_name}
Author: GG Dev
Author URI: https://gg-dev.co/
Template: {$parent_slug}
Version: 1.0.0
License: GNU General Public License v2 or later
Text Domain: {$child_slug}
*/

/* Put your overrides below. */
";

	// functions.php: enqueue child CSS after parent handle `ggdevclienttheme-style`
	$functions_php = "<?php\nif (!defined('ABSPATH')) { exit; }\n\nadd_action('wp_enqueue_scripts', function () {\n\twp_enqueue_style(\n\t\t'{$child_slug}-style',\n\t\tget_stylesheet_directory_uri() . '/style.css',\n\t\tarray('ggdevclienttheme-style'),\n\t\twp_get_theme()->get('Version')\n\t);\n}, 20);\n";

	// Write files
	if (false === file_put_contents($child_path . '/style.css', $style_css)) {
		return new WP_Error('write_style', __('Could not write style.css', 'ggdevclienttheme'));
	}
	if (false === file_put_contents($child_path . '/functions.php', $functions_php)) {
		return new WP_Error('write_functions', __('Could not write functions.php', 'ggdevclienttheme'));
	}

	// Copy screenshot
	$parent_screenshot = trailingslashit(get_template_directory()) . 'screenshot.png';
	if (file_exists($parent_screenshot)) {
		@copy($parent_screenshot, $child_path . '/screenshot.png');
	}

	// Optionally activate
	$activated = false;
	if ($activate) {
		$theme_obj = wp_get_theme($child_slug);
		if ($theme_obj->exists()) {
			switch_theme($child_slug);
			$activated = true;
		}
	}

	return array('activated' => $activated);
}
