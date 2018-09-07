(function ($, Drupal, drupalSettings) {

  "use strict";

  Drupal.behaviors.magnific_popup = {
    attach: function (context, settings) {
      // Gallery.
      $(context).find('.mfp-all-items, .mfp-first-item, .mfp-random-item').once('mfp-processed').each( function() {
        $(this).magnificPopup({
          delegate: 'a',
          type: 'image',
          gallery: {
            enabled: true
          },
          image: {
            titleSrc: function (item) {
              return item.img.attr('alt') || '';
            }
          }
        });
      });

      // Separate items.
      $(context).find('.mfp-separate-items').once('mfp-processed').each(function () {
        $(this).magnificPopup({
          delegate: 'a',
          type: 'image',
          image: {
            titleSrc: function (item) {
              return item.img.attr('alt') || '';
            }
          }
        });
      });
    }
  };

})(jQuery, Drupal, drupalSettings);
