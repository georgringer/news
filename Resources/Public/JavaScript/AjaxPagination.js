$(document).ready(function () {
    $('.news').on('click', '.page-navigation a', function (e) {
        var ajaxUrl = $(this).data('link');
        if (ajaxUrl !== undefined && ajaxUrl !== '') {
            e.preventDefault();
            var container = 'news-container-' + $(this).data('container');
            $.ajax({
                url: ajaxUrl,
                type: 'GET',
                success: function (result) {
                    var ajaxDom = $(result).find('#' + container);
                    $('#' + container).replaceWith(ajaxDom);
                }
            });
        }
    });
});