define(['jquery'], function ($) {

    var table = $('.news-table');



    $(table).each(function() {
        if ($(this).width() < 350) {
            $(this).addClass('news-table-small');
        }
    });

    $('.news-table tfoot a').click(function (e, element) {
        $(this).toggleClass('open');
        $('#' + $(this).data('identifier')).toggleClass('hidden');
        e.preventDefault();
    });

});