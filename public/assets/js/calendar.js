(function($) {
  'use strict';
  $(function() {
    if ($('#calendar').length) {
      $('#calendar').fullCalendar({
        header: {
          left: 'prev,next today',
          center: 'title',
          //right: 'month,basicWeek,basicDay'
            right: 'month'
        },
        defaultDate: '2021-06-05',
        navLinks: true, // can click day/week names to navigate views
        editable: true,
        eventLimit: true, // allow "more" link when too many events
        events: [{
           title: 'Inspection Schedule',
            start: '2021-06-15'
          },
          {
             title: 'Inspection Schedule',
            start: '2021-06-21'
          },
          {
           title: 'Inspection Schedule',
            start: '2021-06-30'
          },
          {
            title: 'Inspection Schedule',
            start: '2021-06-10'
          },
          {
            title: 'Schedule Now',
            url: '#',
            start: '2021-06-08',
            className: 'myBtn'
          }
        ]
      })
    }
  });
})(jQuery);