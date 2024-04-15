$(".toggle-password").click(function() {

$(this).toggleClass("fa-eye fa-eye-slash");
var input = $($(this).attr("toggle"));
if (input.attr("type") == "password") {
  input.attr("type", "text");
} else {
  input.attr("type", "password");
}
});

$( ".major" ).click(function() {
  $( "#major" ).slideToggle( "slow", function() {
    // Animation complete.
  });
});

// $(document).ready(function(){
//   $('.dropdown-submenu a.test').on("click", function(e){
//     $(this).next('ul').toggle();
//     e.stopPropagation();
//     e.preventDefault();
//   });
// });

$(document).ready(function(){
  $("#notificationDropdown").click(function(){
    $(".nf-dropdown").toggleClass('show');
  });
});

//  if ($("#morris-donut-example").length) {
//     Morris.Donut({
//       element: 'morris-donut-example',
//       colors: ['#76C1FA', '#63CF72', '#F36368', '#FABA66'],
//       data: [{
//           label: "Pending",
//           value: 100
//         },
//         {
//           label: "Approved",
//           value: 200
//         },
//         {
//           label: "Process",
//           value: 50
//         }
//       ]
//     });
//   }

$("#year_picker").datepicker({
    format: "yyyy",
    viewMode: "years", 
    minViewMode: "years",
	year: 2017
});

  var g8 = new JustGage({
    id: 'g8',
    value: 600,
    min: 0,
    max: 1000,
    reverse: true,
    gaugeWidthScale: 0.6,
    counter: true
  });
  
  (function ($) {
    "use strict";

    $('.incentive-trigger').on('click', function () {
        $(this).parent().toggleClass('switcher-palate');
    });

    
}(jQuery));
  
(function ($) {
  "use strict";

  $('.incentive-trigger2').on('click', function () {
      $(this).parent().toggleClass('switcher-palate2');
  });

  
}(jQuery));

(function($){
  $(window).on("load",function(){
      $(".content").mCustomScrollbar();
  });
})(jQuery);
