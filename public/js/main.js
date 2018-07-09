$(document).ready(function () {
    $("#sidebar").mCustomScrollbar({
        theme: "minimal-dark"
    });

    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
        $('#container').toggleClass('sidebar-active');
    });

    $('#sidebar a.active').toggleClass('text-primary');

    if(window.matchMedia("(max-width: 425px)").matches) {
        $('div#input-pesquisar').toggleClass('d-none');
        $('#menu').prepend("<li class=\"nav-item\">\n" +
            "                    <div id=\"input-pesquisar\" class=\"input-group\">\n" +
            "                        <input class=\"form-control\" type=\"search\" placeholder=\"Pesquisar\" aria-label=\"\">\n" +
            "                        <div class=\"input-group-append\">\n" +
            "                            <button type=\"button\" class=\"btn btn-outline-dark\">\n" +
            "                                <i class=\"fas fa-search\"></i>\n" +
            "                            </button>\n" +
            "                        </div>\n" +
            "                    </div>\n" +
            "                </li>");
    }
});