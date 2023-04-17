/**
 * @file
 * Click functionality
 */
(function($, Drupal) {
  let animating = false;

  $(document).ready(function() {
    $('.click-link').on('click', function (event) {
      let target = $(this).data('tab');
      $(target).addClass('active');

      animateToTarget(target, event, false);
    });

    $('.click-home').on('click', function (event) {
      animateToTarget('#page', event, true);
    });
  });

  $('.quicktabs-main .quicktabs-tabpage, .navtabs-main .navtabs-tabpage').each(function(index, element) {
    let tabElement = $(element);

    tabElement.each(function() {
      ScrollTrigger.create({
        trigger: tabElement,
        start: "top top+=25%",
        end: "bottom top",
        onLeaveBack: () => {
          if ($(element).is(':visible')) {
            animateToTarget('#page', null, true);
          }
        },
      });
    });
  });

  function animateToTarget(selector, e, close) {
    const elem = selector ? document.querySelector(selector) : false;

    if(elem) {
      if(e) e.preventDefault();
      if (!animating) {
        gsap.to(window, {
          scrollTo: elem,
          onStart: () => {
            animating = true;
          },
          onComplete: function() {
            if (close === true) {
              $('.quicktabs-main .quicktabs-tabpage.active, .navtabs-main .navtabs-tabpage.active').removeClass('active');
            }

            animating = false;
          }
        });
      }
    }
  }
})(jQuery, Drupal);
