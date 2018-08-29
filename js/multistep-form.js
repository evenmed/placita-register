var current_fs, next_fs, previous_fs; //fieldsets
var left, opacity, scale; //fieldset properties which we will animate
var animating; //flag to prevent quick multi-click glitches

jQuery(".next").click(function() {
  if (animating) return false;
  animating = true;

  current_fs = jQuery(this).closest("fieldset");
  next_fs = jQuery(this)
    .closest("fieldset")
    .next("fieldset");

  if (!validate_fieldset(current_fs)) {
    animating = false;
    return false;
  }

  //activate next step on progressbar using the index of next_fs
  jQuery(".progressbar li")
    .eq(jQuery("fieldset").index(next_fs))
    .addClass("active");

  //show the next fieldset
  next_fs.show();
  //hide the current fieldset with style
  current_fs
    .css("position", "absolute")
    .css("width", "calc(100% - 30px)")
    .animate(
      { opacity: 0 },
      {
        step: function(now, mx) {
          scale = 1 - (1 - now) * 0.2;
          opacity = 1 - now;
          current_fs.css({ transform: "scale(" + scale + ")" });
          next_fs.css({ left: left, opacity: opacity });
        },
        duration: 800,
        complete: function() {
          current_fs.hide();
          current_fs.css("position", "").css("width", "100%");
          animating = false;
        },
        //this comes from the custom easing plugin
        easing: "easeInOutBack"
      }
    );
});

jQuery(".previous").click(function() {
  if (animating) return false;
  animating = true;

  current_fs = jQuery(this).closest("fieldset");
  previous_fs = jQuery(this)
    .closest("fieldset")
    .prev("fieldset");

  //de-activate current step on progressbar
  jQuery(".progressbar li")
    .eq(jQuery("fieldset").index(current_fs))
    .removeClass("active");

  //show the previous fieldset
  previous_fs
    .css("position", "absolute")
    .css("width", "calc(100% - 30px)")
    .show();
  //hide the current fieldset with style
  current_fs.animate(
    { opacity: 0 },
    {
      step: function(now, mx) {
        scale = 0.8 + (1 - now) * 0.2;
        opacity = 1 - now;
        current_fs.css({ left: left });
        previous_fs.css({
          transform: "scale(" + scale + ")",
          opacity: opacity
        });
      },
      duration: 800,
      complete: function() {
        current_fs.hide();
        previous_fs.css("position", "").css("width", "100%");
        animating = false;
      },
      //this comes from the custom easing plugin
      easing: "easeInOutBack"
    }
  );
});

function validate_fieldset($fs) {
  var inputs = $fs.find(":input[required]"),
    errorInputs = [];
  jQuery.each(inputs, function(k, v) {
    if (jQuery(this).attr("type") == "radio") {
      var name = jQuery(this).attr("name");
      if (!jQuery("input[name=" + name + "]:checked").length)
        errorInputs.push(jQuery(this));
    } else if (
      jQuery(this).attr("type") != "checkbox" &&
      !jQuery(this).hasClass("chosen-search-input")
    ) {
      if (!jQuery(this).val() || jQuery(this).val() == "")
        errorInputs.push(jQuery(this));
      else if (
        jQuery(this).attr("type") == "email" &&
        !validateEmail(jQuery(this).val())
      )
        errorInputs.push(jQuery(this));
    }
  });
  if (errorInputs.length > 0) {
    jQuery.each(errorInputs, function(k, v) {
      markError(jQuery(this));
    });
    jQuery("html, body").animate(
      {
        scrollTop: jQuery(".hasError:visible:first").offset().top - 20
      },
      100
    );
    return false;
  } else {
    return true;
  }
}

function markError($this) {
  if ($this.hasClass("hasError")) {
    if ($this.attr("type") !== "radio") {
      animating = true;
      $this.css("border-color", "#F79421");
      setTimeout(function() {
        $this.css("border-color", "");
        animating = false;
      }, 150);
    } else {
      animating = true;
      $this.next(".outer").css("border-color", "#F79421");
      setTimeout(function() {
        $this.next(".outer").css("border-color", "");
        animating = false;
      }, 150);
    }
  } else {
    $this.addClass("hasError");
  }
}

function validateEmail($email) {
  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
  return emailReg.test($email);
}
