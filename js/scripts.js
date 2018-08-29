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
