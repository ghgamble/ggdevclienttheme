<?php get_header(); ?>

<main id="primary" class="site-main" role="main" tabindex="-1" aria-labelledby="error-title" style="padding: 6rem 1rem 4rem 1rem; text-align: center; background-color: #fff; min-height: 65vh;">

    <div style="max-width: 700px; margin: 0 auto; padding: 2rem;">
        <h1 id="error-title" style="font-size: 2.5rem; color: #2c3e50; margin-bottom: 1rem;">Page Not Found</h1>
        <p style="font-size: 1.25rem; color: #555; line-height: 1.6; margin-bottom: 2rem;">
            We're sorry, but the page you were looking for isn’t here.<br>
            It may have been moved, or perhaps the address was typed incorrectly.
        </p>
        <a href="<?php echo esc_url(home_url('/')); ?>" 
           style="display: inline-block; margin-top: 1rem; padding: 0.75rem 1.5rem; background-color: #fff; border: 2px solid #000; color: #000; text-decoration: none; border-radius: 4px; font-size: 1rem; outline: 2px solid transparent;">
            Back to Home
        </a>
    </div>

</main>

<?php get_footer(); ?>
