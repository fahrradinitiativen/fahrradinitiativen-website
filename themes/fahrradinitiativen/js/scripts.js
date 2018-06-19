(function ($, Drupal) {

    // Drupal behaviour function "attach"
    // Called on page load and after ajax response to init js again
    Drupal.behaviors.carousel = {
        attach: function (context, settings) {
            $(window).on("scroll", function () {
                var fromTop = $(window).scrollTop();
                $("body").toggleClass("scrolled", (fromTop > 150));
            });
            
            var sticky = new Waypoint.Sticky({
              element: $('#navbar')[0]
            });

            var bigSlideAPI = ($('.mobile-menu-trigger').bigSlide({
                side: 'right',
                menuWidth: "290px",
                menu: '#menu-mobile',
                easyClose: 'true'
            })).bigSlideAPI;
            $('.mobile-menu-close').click(function() {
              bigSlideAPI.view.toggleClose();
            });
            
            var bigSlideAPI2 = ($('.mobile-quicklinks-trigger').bigSlide({
                side: 'left',
                menuWidth: "290px",
                menu: '#menu-quicklinks',
                easyClose: 'true'
            })).bigSlideAPI2;
            $('.mobile-quicklinks-close').click(function() {
              bigSlideAPI2.view.toggleClose();
            });
            
        }
    };

})(jQuery, Drupal);