(function($) {
  'use strict';
  $(function() {
    if ($('#calendar1').length) {
      $('#calendar1').fullCalendar({
        header: {
          left: 'prev,next today',
          center: 'title',
          //right: 'month,basicWeek,basicDay'
           right: 'month'
        },
        defaultDate: '2021-07-07',
        navLinks: true, // can click day/week names to navigate views
        editable: true,
        eventLimit: true, // allow "more" link when too many events
        
          events: [{
           title: 'Slots Available',
            start: '2021-07-12'
          },
          {
             title: 'Slots Available',
            start: '2021-07-13'
          },
          {
           title: 'Slots Available',
            start: '2021-07-14'
          },
          {
            title: 'Slots Available',
            start: '2021-07-15'
          },
                 {
            title: 'Slots Available',
            start: '2021-07-16'
          }
         
        ]
      })
    }
  });
})(jQuery);