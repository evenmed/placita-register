(function($) {
  $("input.date").datepicker();
  $("form").on("change keyup paste", ".hasError", function() {
    if ($(this).attr("type") == "radio") {
      var name = $(this).attr("name");
      $("input[name=" + name + "]").removeClass("hasError");
    } else {
      $(this).removeClass("hasError");
    }
  });

  $(".birthdate").datepicker("option", "maxDate", "+0");
  $(".birthdate").datepicker("option", "defaultDate", "-2m");

  $(".baptism_date").datetimepicker({
    disabledWeekDays: [1, 2, 3, 4],
    allowTimes: ["7:30", "9:30", "11:30", "13:15", "13:30", "15:15"],
    format: "m/d/Y H:i",
    minDate: 0,
    maxDate: '+1971/01/01',
    inline: true,
    scrollMonth: false,
    scrollTime: false,
    scrollInput: false,
    onSelectDate: function(ct, $this) {
      const day = ct.getDay();
      const times = [];
      switch (day) {
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

      $this.datetimepicker("setOptions", { allowTimes: times });
    },
    onSelectTime: function(ct, $this) {
      $this.blur();
    }
  });

  $.each($(".chosen-select"), function(k, v) {
    $(this).on("chosen:ready", function() {
      if ($(this).val() != null) {
        var id = $(this).attr("id");
        id = id.replace("-", "_") + "_chosen"; // form chosen select id
        console.log(id);
        $("#" + id)
          .find(".chosen-single")
          .css("color", "#fff");
      }
    });
  });
  $(".chosen-select").chosen({
    width: "100%",
    inherit_select_classes: true,
    display_disabled_options: false
  });
  $(".chosen-select").on("change", function(evt, params) {
    var id = $(this).attr("id");
    id = id.replace("-", "_") + "_chosen"; // form chosen select id
    $("#" + id)
      .find(".chosen-single")
      .css("color", "white");
  });
  $(".chosen-select").on("chosen:showing_dropdown", function(evt, params) {
    var id = $(this).attr("id");
    id = id.replace("-", "_") + "_chosen"; // form chosen select id

    var $this = $("#" + id),
      $wrap = $("#register_form-wrap"),
      dropOffset =
        $this.find(".chosen-drop").offset().top +
        $this.find(".chosen-drop").outerHeight(),
      wrapOffset = $wrap.offset().top + $wrap.outerHeight();

    $this.find('.chosen-drop .chosen-search input[type="text"]').show();

    if (dropOffset > wrapOffset) {
      var overflow = dropOffset - wrapOffset;
      $this.find(".chosen-results").css("max-height", 240 - overflow);
    }
  });
  $(".chosen-select").on("chosen:hiding_dropdown", function(evt, params) {
    var id = $(this).attr("id");
    id = id.replace("-", "_") + "_chosen"; // form chosen select id
    var $this = $("#" + id);
    $this.find('.chosen-drop .chosen-search input[type="text"]').hide();
  });
})(jQuery);
