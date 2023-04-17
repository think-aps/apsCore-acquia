/**
 * @file
 * Global utilities.
 *
 */
(function (Drupal) {

  'use strict';

  Drupal.behaviors.bootstrap_barrio = {
    attach: function (context, settings) {

      var position = window.scrollY;
      window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
          document.querySelector('body').classList.add("scrolled");
        }
        else {
          document.querySelector('body').classList.remove("scrolled");
        }
        var scroll = window.scrollY;
        if (scroll > position) {
          document.querySelector('body').classList.add("scrolldown");
          document.querySelector('body').classList.remove("scrollup");
        } else {
          document.querySelector('body').classList.add("scrollup");
          document.querySelector('body').classList.remove("scrolldown");
        }
        position = scroll;
      });

      document.addEventListener('click', function (event) {

        // If the clicked element doesn't have the right selector, bail
        if (!event.target.matches('.dropdown-item a.dropdown-toggle')) return;
      
        // Don't follow the link
        event.preventDefault();
      
        toggle(event.target.next('ul'));
        event.stopPropagation();
      
      }, false);
      
      // Toggle element visibility
      var toggle = function (elem) {

        // If the element is visible, hide it
        if (window.getComputedStyle(elem).display === 'block') {
          hide(elem);
          return;
        }

        // Otherwise, show it
        show(elem);

      };
      
      var show = function (elem) {
        elem.style.display = 'block';
      };
      
      var hide = function (elem) {
        elem.style.display = 'none';
      };
    }
  };

})(Drupal);

//# sourceMappingURL=data:application/json;charset=utf8;base64,eyJ2ZXJzaW9uIjozLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiIiwic291cmNlcyI6WyJiYXJyaW8uanMiXSwic291cmNlc0NvbnRlbnQiOlsiLyoqXG4gKiBAZmlsZVxuICogR2xvYmFsIHV0aWxpdGllcy5cbiAqXG4gKi9cbihmdW5jdGlvbiAoRHJ1cGFsKSB7XG5cbiAgJ3VzZSBzdHJpY3QnO1xuXG4gIERydXBhbC5iZWhhdmlvcnMuYm9vdHN0cmFwX2JhcnJpbyA9IHtcbiAgICBhdHRhY2g6IGZ1bmN0aW9uIChjb250ZXh0LCBzZXR0aW5ncykge1xuXG4gICAgICB2YXIgcG9zaXRpb24gPSB3aW5kb3cuc2Nyb2xsWTtcbiAgICAgIHdpbmRvdy5hZGRFdmVudExpc3RlbmVyKCdzY3JvbGwnLCBmdW5jdGlvbigpIHtcbiAgICAgICAgaWYgKHdpbmRvdy5zY3JvbGxZID4gNTApIHtcbiAgICAgICAgICBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCdib2R5JykuY2xhc3NMaXN0LmFkZChcInNjcm9sbGVkXCIpO1xuICAgICAgICB9XG4gICAgICAgIGVsc2Uge1xuICAgICAgICAgIGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJ2JvZHknKS5jbGFzc0xpc3QucmVtb3ZlKFwic2Nyb2xsZWRcIik7XG4gICAgICAgIH1cbiAgICAgICAgdmFyIHNjcm9sbCA9IHdpbmRvdy5zY3JvbGxZO1xuICAgICAgICBpZiAoc2Nyb2xsID4gcG9zaXRpb24pIHtcbiAgICAgICAgICBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCdib2R5JykuY2xhc3NMaXN0LmFkZChcInNjcm9sbGRvd25cIik7XG4gICAgICAgICAgZG9jdW1lbnQucXVlcnlTZWxlY3RvcignYm9keScpLmNsYXNzTGlzdC5yZW1vdmUoXCJzY3JvbGx1cFwiKTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCdib2R5JykuY2xhc3NMaXN0LmFkZChcInNjcm9sbHVwXCIpO1xuICAgICAgICAgIGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJ2JvZHknKS5jbGFzc0xpc3QucmVtb3ZlKFwic2Nyb2xsZG93blwiKTtcbiAgICAgICAgfVxuICAgICAgICBwb3NpdGlvbiA9IHNjcm9sbDtcbiAgICAgIH0pO1xuXG4gICAgICBkb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGZ1bmN0aW9uIChldmVudCkge1xuXG4gICAgICAgIC8vIElmIHRoZSBjbGlja2VkIGVsZW1lbnQgZG9lc24ndCBoYXZlIHRoZSByaWdodCBzZWxlY3RvciwgYmFpbFxuICAgICAgICBpZiAoIWV2ZW50LnRhcmdldC5tYXRjaGVzKCcuZHJvcGRvd24taXRlbSBhLmRyb3Bkb3duLXRvZ2dsZScpKSByZXR1cm47XG4gICAgICBcbiAgICAgICAgLy8gRG9uJ3QgZm9sbG93IHRoZSBsaW5rXG4gICAgICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG4gICAgICBcbiAgICAgICAgdG9nZ2xlKGV2ZW50LnRhcmdldC5uZXh0KCd1bCcpKTtcbiAgICAgICAgZXZlbnQuc3RvcFByb3BhZ2F0aW9uKCk7XG4gICAgICBcbiAgICAgIH0sIGZhbHNlKTtcbiAgICAgIFxuICAgICAgLy8gVG9nZ2xlIGVsZW1lbnQgdmlzaWJpbGl0eVxuICAgICAgdmFyIHRvZ2dsZSA9IGZ1bmN0aW9uIChlbGVtKSB7XG5cbiAgICAgICAgLy8gSWYgdGhlIGVsZW1lbnQgaXMgdmlzaWJsZSwgaGlkZSBpdFxuICAgICAgICBpZiAod2luZG93LmdldENvbXB1dGVkU3R5bGUoZWxlbSkuZGlzcGxheSA9PT0gJ2Jsb2NrJykge1xuICAgICAgICAgIGhpZGUoZWxlbSk7XG4gICAgICAgICAgcmV0dXJuO1xuICAgICAgICB9XG5cbiAgICAgICAgLy8gT3RoZXJ3aXNlLCBzaG93IGl0XG4gICAgICAgIHNob3coZWxlbSk7XG5cbiAgICAgIH07XG4gICAgICBcbiAgICAgIHZhciBzaG93ID0gZnVuY3Rpb24gKGVsZW0pIHtcbiAgICAgICAgZWxlbS5zdHlsZS5kaXNwbGF5ID0gJ2Jsb2NrJztcbiAgICAgIH07XG4gICAgICBcbiAgICAgIHZhciBoaWRlID0gZnVuY3Rpb24gKGVsZW0pIHtcbiAgICAgICAgZWxlbS5zdHlsZS5kaXNwbGF5ID0gJ25vbmUnO1xuICAgICAgfTtcbiAgICB9XG4gIH07XG5cbn0pKERydXBhbCk7XG4iXSwiZmlsZSI6ImJhcnJpby5qcyJ9
