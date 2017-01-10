define(['jquery', 'TYPO3/CMS/Backend/Tooltip', 'TYPO3/CMS/Backend/ClickMenu'], function ($) {

    $(document).ready(function () {
        $('a[data-togglelink="1"]').click(function (e) {
            e.preventDefault();
            $('#setting-container').toggle();
        });
    });

});