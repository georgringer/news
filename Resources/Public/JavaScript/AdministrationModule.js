define(['jquery', 'TYPO3/CMS/Backend/Tooltip'], function ($) {

    if ($('.t3js-clearable').length) {
        require(['TYPO3/CMS/Backend/jquery.clearable'], function() {
            $('.t3js-clearable').clearable();
        });
    }

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