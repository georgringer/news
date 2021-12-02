define(['jquery', 'TYPO3/CMS/Backend/Tooltip', 'TYPO3/CMS/Backend/Input/Clearable'], function ($) {

  var clearables = Array.from(document.querySelectorAll('.t3js-clearable')).filter(inputElement => {
    // Filter input fields being a date time picker and a color picker
    return !inputElement.classList.contains('t3js-datetimepicker') && !inputElement.classList.contains('t3js-color-picker');
  });
  clearables.forEach(clearableField => clearableField.clearable());
  $(document).ready(function () {
    var form = $('#administrationForm');
    if (form.data('autosubmitform') == 1) {
      form.submit();
    }
    $('a[data-togglelink="1"]').click(function (e) {
      e.preventDefault();
      $('#setting-container').toggle();
    });
  });

});
