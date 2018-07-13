$(document).ready(function () {
    $("#sidebar").mCustomScrollbar({
        theme: "minimal-dark"
    });

    $('#sidebarAppear').on('click', function () {
        $('#sidebar').toggleClass('active');
        $('#container').toggleClass('sidebarActive');
    });

    $('#sidebar a.active').toggleClass('text-primary');

    if (window.matchMedia("(max-width: 425px)").matches) {
        $('div#pesquisar').toggleClass('d-none');
        $('#sidebarItens').prepend("<li class=\"nav-item\">\n" +
            "                    <div id=\"pesquisar\" class=\"input-group\">\n" +
            $('div#pesquisar').html() +
            "                    </div>\n" +
            "                </li>");
    }
});