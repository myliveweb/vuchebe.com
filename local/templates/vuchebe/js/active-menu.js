$('document').ready(function() {
    $('.st-top-menu .st-menu-li a').each(function() {
        if ('http://vuchebe.com'+$(this).attr('href') == window.location.href)
        {
            $(this).addClass('active');
        }
    });
}); 
