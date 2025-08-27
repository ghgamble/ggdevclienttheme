import '../scss/style.scss';

document.addEventListener('DOMContentLoaded', () => {
  const menuToggle = document.querySelector('.menu-toggle');
  const mobileMenu = document.querySelector('.main-mobile-menu');
  const siteHeader = document.querySelector('.site-header');
  const skipLink = document.querySelector('.skip-to-content');

  // Toggle mobile menu visibility
  if (menuToggle && mobileMenu) {
    menuToggle.setAttribute('aria-controls', 'main-navigation'); // NEW
    menuToggle.addEventListener('click', () => {
      const isOpen = mobileMenu.classList.contains('open');
      mobileMenu.classList.toggle('open');
      siteHeader.classList.toggle('menu-open');
      menuToggle.setAttribute('aria-expanded', (!isOpen).toString());
      mobileMenu.setAttribute('aria-hidden', isOpen.toString());
    });
  }

  // Mobile sub-menu toggle accessibility
  if (window.innerWidth <= 768) {
    document.querySelectorAll('.dropdown-arrow').forEach((arrow) => {
      // Ensure focusable and screen reader friendly
      arrow.setAttribute('role', 'button');
      arrow.setAttribute('tabindex', '0');
      arrow.setAttribute('aria-label', 'Toggle submenu');

      arrow.addEventListener('click', (e) => {
        e.preventDefault();
        const parentLi = arrow.closest('li');
        const subMenu = parentLi.querySelector('.sub-menu');
        if (subMenu) {
          const isOpen = subMenu.classList.contains('open');
          subMenu.classList.toggle('open');
          const menuItemLink = parentLi.querySelector('a');
          menuItemLink?.setAttribute('aria-expanded', (!isOpen).toString());
        }
      });

      arrow.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          arrow.click();
        }
      });
    });
  }

  // Skip to content link visibility
  if (skipLink) {
    window.addEventListener('scroll', () => {
      if (window.scrollY > 100) {
        skipLink.classList.add('visible');
      } else {
        skipLink.classList.remove('visible');
      }
    });
  }
});
