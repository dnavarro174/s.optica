(function($) {
  'use strict';
  if ($("#timepicker-example").length) {
    $('#timepicker-example').datetimepicker({
      format: 'LT'
    });
  }
  if ($(".color-picker").length) {
    $('.color-picker').asColorPicker();
  }
  // d-m-Y
  if ($("#datepicker-popup").length) {
    $('#datepicker-popup').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      format: 'dd/mm/yyyy'
    });
  }
  if ($("#datepicker-popup2").length) {
    $('#datepicker-popup2').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      format: 'dd/mm/yyyy'
    });
  }
  // show month
  if ($("#datepicker-popup3").length) {
    $('#datepicker-popup3').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      format: 'mm/yyyy'
    });
  }
  if ($("#datepicker-popup4").length) {
    $('#datepicker-popup4').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      format: 'mm/yyyy'
    });
  }
  if ($("#inline-datepicker").length) {
    $('#inline-datepicker').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
    });
  }
  if ($(".datepicker-autoclose").length) {
    $('.datepicker-autoclose').datepicker({
      autoclose: true
    });
  }
  if ($('input[name="date-range"]').length) {
    $('input[name="date-range"]').daterangepicker();
  }
  if ($('input[name="date-time-range"]').length) {
    $('input[name="date-time-range"]').daterangepicker({
      timePicker: true,
      timePickerIncrement: 30,
      locale: {
        format: 'MM/DD/YYYY h:mm A'
      }
    });
  }
})(jQuery);