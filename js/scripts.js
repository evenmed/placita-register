(function($) {

  // Initiate datepicker
  $("input.date").datepicker();
  $(".birthdate").datepicker("option", "maxDate", "+0");
  $(".birthdate").datepicker("option", "defaultDate", "-2m");

  // Remove errors on change
  $("form").on("change keyup paste", ".hasError", function() {
    if ($(this).attr("type") == "radio") {
      var name = $(this).attr("name");
      $("input[name=" + name + "]").removeClass("hasError");
    } else {
      $(this).removeClass("hasError");
    }
  });

  // Initiate datetimepicker
  $.datetimepicker.setLocale( server_data.locale.substr(0, 2) ); // Set language
  $(".baptism_date").datetimepicker({
    disabledWeekDays: [1, 2, 3, 4],
    allowTimes: ["7:30", "9:30", "11:30", "13:15", "13:30", "15:15"],
    format: "m/d/Y H:i",
    minDate: 0,
    maxDate: "+01/01/1971",
    formatDate: "m/d/Y",
    inline: true,
    scrollMonth: false,
    scrollTime: false,
    scrollInput: false,
    onSelectDate: function(ct, $this) {
      const weekday = ct.getDay();
      const times = [];

      // This will return an array of times in h:m format
      const unavailable_times = getUnavailableTimesForDate(ct);

      switch (weekday) {
        case 0: // Sunday
          times.push("9:30", "11:30", "13:30");
          break;
        case 5: // Friday
          times.push("13:30");
          break;
        case 6: // Saturday
          times.push("7:30", "9:30", "11:30", "13:15", "15:15");
          break;
      }

      // remove unavailable times (only keep the times that aren't in the unavailable_times array)
      available_times = times.filter( time => unavailable_times.indexOf(time) == -1 );

      $this.datetimepicker("setOptions", { allowTimes: available_times });
    },
    onSelectTime: function(ct, $this) {
      $this.blur();
    }
  });

  // Validate baptism date before submitting
  $('#register-form .submit').click( function(e) {
    e.preventDefault();
    const b_times = server_data.baptism_times;
    const date    = $(".baptism_date").datetimepicker('getValue');
    const weekday = date.getDay();
    const hours   = date.getHours();
    const minutes = date.getMinutes();
    const time    = hours + ':' + minutes;
    if ( b_times[weekday].indexOf(time) > -1 ) {

      // It's a valid weekday / time, now make sure it's not part of the unavailable array

      // This will return an array of times in h:m format
      const unavailable_times = getUnavailableTimesForDate(date);

      if ( unavailable_times.indexOf(time) == -1 ) {
        $('#register-form').submit();
        return true;
      }

    }
    markError( $(".baptism_date") );
  });

  function getUnavailableTimesForDate( date ) {
    const year    = date.getFullYear();
    const month   = date.getMonth() + 1; // +1 bc getMonth returns month no. from 0-11
    const day     = date.getDate();

    // This will return an array of times in h:m format
    return server_data.unavailable_dates.filter( datetime => {
      return (
          datetime['year'] == year &&
          datetime['month'] == month &&
          datetime['day'] == day
      );
    } ).map( datetime => `${datetime['hour']}:${datetime['minute']}` );

  }

})(jQuery);
