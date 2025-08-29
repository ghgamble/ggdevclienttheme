<main id="primary" class="site-main" tabindex="-1">

	<?php
	/* ===== Build a clean archive/blog title ===== */
	ob_start();
	if ( is_home() ) {
		// Blog posts index (Posts page or fallback to "Blog")
		$posts_page_id = (int) get_option( 'page_for_posts' );
		if ( $posts_page_id && ! is_front_page() ) {
			echo esc_html( get_the_title( $posts_page_id ) );
		} else {
			echo esc_html__( 'Blog', 'ggdevclienttheme' );
		}
	} elseif ( is_category() ) {
		single_cat_title();
	} elseif ( is_tag() ) {
		single_tag_title();
	} elseif ( is_tax() ) {
		single_term_title();
	} elseif ( is_author() ) {
		echo esc_html( get_the_author() );
	} elseif ( is_day() ) {
		echo esc_html( get_the_date() );
	} elseif ( is_month() ) {
		echo esc_html( get_the_date( _x( 'F Y', 'monthly archives date format', 'ggdevclienttheme' ) ) );
	} elseif ( is_year() ) {
		echo esc_html( get_the_date( _x( 'Y', 'yearly archives date format', 'ggdevclienttheme' ) ) );
	} elseif ( is_post_type_archive() ) {
		post_type_archive_title();
	} else {
		echo esc_html( wp_strip_all_tags( get_the_archive_title() ) );
	}
	$archive_title = trim( wp_strip_all_tags( ob_get_clean() ) );
	?>

	<?php if ( have_posts() ) : ?>

		<div class="o-container">
			<div class="archive-masonry max-3">
				<?php while ( have_posts() ) : the_post(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'masonry-card' ); ?>>
						<?php if ( has_post_thumbnail() ) : ?>
							<a class="card-thumb" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
								<?php the_post_thumbnail( 'large', array( 'loading' => 'lazy', 'decoding' => 'async' ) ); ?>
							</a>
						<?php endif; ?>

						<div class="card-body">
							<time class="card-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
								<?php echo esc_html( get_the_date() ); ?>
							</time>

							<h2 class="entry-title">
								<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
							</h2>

							<div class="excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 10, '…' ) ); ?></div>
						</div>
					</article>
				<?php endwhile; ?>
			</div>
		</div>

		<nav class="pagination" role="navigation" aria-label="<?php esc_attr_e( 'Post navigation', 'ggdevclienttheme' ); ?>">
			<?php the_posts_pagination(); ?>
		</nav>

	<?php else : ?>

		<div class="o-container">
			<h2><?php esc_html_e( 'No posts found', 'ggdevclienttheme' ); ?></h2>
			<p><?php esc_html_e( 'We don’t have blog posts to display yet. Try a search or browse categories below.', 'ggdevclienttheme' ); ?></p>
			<?php get_search_form(); ?>
			<nav class="no-posts-cats" aria-label="<?php esc_attr_e( 'Browse categories', 'ggdevclienttheme' ); ?>">
				<ul><?php wp_list_categories( array( 'title_li' => '' ) ); ?></ul>
			</nav>
		</div>

	<?php endif; ?>

</main>
