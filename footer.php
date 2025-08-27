<?php // Skip link placed early in the body ?>
<a href="#content" class="skip-to-content" aria-label="Skip to content">
  <span class="screen-reader-text">Skip to content</span>
  <span class="skip-arrow" aria-hidden="true">
    <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <path d="M18 15l-6-6-6 6"/>
    </svg>
  </span>
</a>

<footer style="background-color: <?php echo esc_attr(get_theme_mod('ggdevclienttheme_footer_bg', '#282b35')); ?>; color: #fff;">
  <div class="o-container footer-inner">
    <div class="footer-social">
      <div class="footer-icons">
        <?php if (is_active_sidebar('footer-social-info')) : ?>
          <?php dynamic_sidebar('footer-social-info'); ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>

</body>
</html>
