<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Register/refresh the "owner" role and wire up capability + UI restrictions.
 */
add_action( 'init', function () {
	$role = get_role( 'owner' );

	$caps = [
		'read'                      => true, // required for wp-admin

		// Posts
		'edit_posts'                => true,
		'publish_posts'             => true,
		'edit_published_posts'      => true,
		'delete_posts'              => true,
		'delete_published_posts'    => true,
		'edit_others_posts'         => true,

		// Pages
		'edit_pages'                => true,
		'publish_pages'             => true,
		'edit_published_pages'      => true,
		'delete_pages'              => true,
		'delete_published_pages'    => true,
		'edit_others_pages'         => true,

		// Media
		'upload_files'              => true,

		// Appearance → Customize & Menus
		'edit_theme_options'        => true, // needed for Menus & many Customizer controls
		'customize'                 => true, // explicit access to customize.php

		// Contact Form 7
		'wpcf7_read_contact_forms'      => true,
		'wpcf7_edit_contact_forms'      => true,
		'wpcf7_delete_contact_forms'    => true,
		'wpcf7_manage_integration'      => true,
	];

	if ( ! $role ) {
		add_role( 'owner', __( 'Owner', 'ggdevclienttheme' ), $caps );
	} else {
		foreach ( $caps as $cap => $grant ) {
			if ( $grant && ! $role->has_cap( $cap ) ) {
				$role->add_cap( $cap );
			}
		}
	}
});

/** Helper: is the current user an Owner (by role)? */
function gg_is_owner_user() {
	$user = wp_get_current_user();
	return $user && in_array( 'owner', (array) $user->roles, true );
}

/**
 * Build a strict allowlist for the Owner sidebar and expose CFDB7 under Contact.
 * Runs very late so it beats plugins that add menus.
 */
add_action( 'admin_menu', function () {
	if ( ! gg_is_owner_user() ) { return; }

	global $menu, $submenu;

	// Top-level menu allowlist (slugs).
	$allow_top = [
		'edit.php',                  // Posts
		'upload.php',                // Media
		'edit.php?post_type=page',   // Pages
		'wpcf7',                     // Contact (CF7)
		'themes.php',                // Appearance (we'll prune its submenus)
	];

	// First, ensure Appearance exists (it will by core), then we'll prune its submenus below.

	// Remove any top-level item that's not whitelisted.
	if ( is_array( $menu ) ) {
		foreach ( $menu as $idx => $item ) {
			$slug = isset( $item[2] ) ? $item[2] : '';
			if ( ! in_array( $slug, $allow_top, true ) ) {
				unset( $menu[ $idx ] );
			}
		}
	}

	// Prune Appearance submenus to only Menus & Customize.
	$allow_appearance_sub = [ 'nav-menus.php', 'customize.php' ];
	if ( isset( $submenu['themes.php'] ) && is_array( $submenu['themes.php'] ) ) {
		foreach ( $submenu['themes.php'] as $i => $sub ) {
			$slug = isset( $sub[2] ) ? $sub[2] : '';
			if ( ! in_array( $slug, $allow_appearance_sub, true ) ) {
				unset( $submenu['themes.php'][ $i ] );
			}
		}
	}

	// Add a visible CFDB7 entry under "Contact" (CF7) that points to our bridge page.
	add_submenu_page(
		'wpcf7',                               // parent: Contact (CF7)
		__( 'Submissions (CFDB7)', 'ggdev' ),
		__( 'Submissions (CFDB7)', 'ggdev' ),
		'upload_files',                        // Owners have this
		'gg-cfdb7-bridge',                     // bridge slug
		'__return_null'                        // actual redirect happens on admin_init
	);

}, 9999 );

/** Early redirect for the CFDB7 bridge (prevents "headers already sent"). */
add_action( 'admin_init', function () {
	if ( ! gg_is_owner_user() ) { return; }

	if ( isset( $_GET['page'] ) && 'gg-cfdb7-bridge' === $_GET['page'] ) {
		if ( ! current_user_can( 'upload_files' ) ) {
			wp_die( esc_html__( 'Sorry, you are not allowed to access this page.' ) );
		}
		wp_safe_redirect( admin_url( 'admin.php?page=cfdb7-list.php' ) );
		exit;
	}
});

/**
 * Allow Owners to manage ANY attachment (media item) without granting broad post powers.
 */
add_filter( 'map_meta_cap', function( $caps, $cap, $user_id, $args ) {
	if ( ! in_array( $cap, [ 'edit_post', 'delete_post' ], true ) ) {
		return $caps;
	}
	$post_id = isset( $args[0] ) ? (int) $args[0] : 0;
	if ( ! $post_id ) { return $caps; }

	$post = get_post( $post_id );
	if ( ! $post || 'attachment' !== $post->post_type ) {
		return $caps; // not media; leave default mapping
	}

	$user = get_userdata( $user_id );
	if ( ! $user || ! in_array( 'owner', (array) $user->roles, true ) ) {
		return $caps;
	}

	if ( user_can( $user_id, 'upload_files' ) ) {
		return [ 'exist' ];
	}
	return $caps;
}, 10, 4 );

/**
 * CFDB7 usually requires `manage_options`. Allow Owners to pass that check
 * ONLY on the CFDB7 list screen. Keep the dot in 'cfdb7-list.php'.
 */
add_filter( 'map_meta_cap', function( $caps, $cap, $user_id, $args ) {
	if ( 'manage_options' !== $cap ) { return $caps; }

	$user = get_userdata( $user_id );
	if ( ! $user || ! in_array( 'owner', (array) $user->roles, true ) ) {
		return $caps;
	}

	$page = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';
	$targets = [ 'cfdb7-list.php', 'cfdb7-list' ];

	if ( is_admin() && in_array( $page, $targets, true ) ) {
		return [ 'exist' ];
	}
	return $caps;
}, 10, 4 );

/** Hide "Administrator" from role dropdowns for non-admins. */
add_filter( 'editable_roles', function( $all_roles ) {
	if ( current_user_can( 'administrator' ) ) { return $all_roles; }
	unset( $all_roles['administrator'] );
	return $all_roles;
});

/** Block promotions to Administrator even if a URL is guessed. */
add_filter( 'map_meta_cap', function( $caps, $cap, $user_id, $args ){
	if ( 'promote_user' !== $cap ) { return $caps; }

	if ( isset( $_REQUEST['role'] ) && 'administrator' === sanitize_key( $_REQUEST['role'] ) && ! current_user_can( 'administrator' ) ) {
		return [ 'do_not_allow' ];
	}
	return $caps;
}, 10, 4 );
