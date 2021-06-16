/*!
    * Start Bootstrap - SB Admin Pro v1.0.1 (https://shop.startbootstrap.com/product/sb-admin-pro)
    * Copyright 2013-2020 Start Bootstrap
    * Licensed under SEE_LICENSE (https://github.com/BlackrockDigital/sb-admin-pro/blob/master/LICENSE)
    */
    (function($) {
  "use strict";

  // Enable Bootstrap tooltips via data-attributes globally
  $('[data-toggle="tooltip"]').tooltip();

  // Enable Bootstrap popovers via data-attributes globally
  $('[data-toggle="popover"]').popover();

  $(".popover-dismiss").popover({
    trigger: "focus"
  });

  // Add active state to sidbar nav links
  var path = window.location.href; // because the 'href' property of the DOM element is the absolute path
  $("#layoutSidenav_nav .sb-sidenav a.nav-link").each(function() {
    if (this.href === path) {
       $(this).addClass("active");

       if( $(this).parent()[0].nodeName == 'NAV' ){
          $(this).parent().parent().addClass('show');
       }
    }
  });

  // Toggle the side navigation
  $("#sidebarToggle").on("click", function(e) {
    e.preventDefault();
    $("body").toggleClass("sb-sidenav-toggled");
  });

  // Activate Feather icons
  feather.replace();

  // Activate Bootstrap scrollspy for the sticky nav component
  $("body").scrollspy({
    target: "#stickyNav",
    offset: 82
  });

  // Scrolls to an offset anchor when a sticky nav link is clicked
  $('.sb-nav-sticky a.nav-link[href*="#"]:not([href="#"])').click(function() {
    if (
      location.pathname.replace(/^\//, "") ==
        this.pathname.replace(/^\//, "") &&
      location.hostname == this.hostname
    ) {
      var target = $(this.hash);
      target = target.length ? target : $("[name=" + this.hash.slice(1) + "]");
      if (target.length) {
        $("html, body").animate(
          {
            scrollTop: target.offset().top - 81
          },
          200
        );
        return false;
      }
    }
  });

  // Click to collapse responsive sidebar
  $("#layoutSidenav_content").click(function() {
    const BOOTSTRAP_LG_WIDTH = 992;
    if (window.innerWidth >= 992) {
      return;
    }
    if ($("body").hasClass("sb-sidenav-toggled")) {
        $("body").toggleClass("sb-sidenav-toggled");
    }
  });

})(jQuery);
