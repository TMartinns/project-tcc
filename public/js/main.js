$(document).ready(function () {
    $("#sidebar").mCustomScrollbar({
        theme: "minimal-dark"
    });

    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
        $('#container').toggleClass('sidebar-active');
    });

    $('#link-pesquisar').on('click', function () {
        $('#input-pesquisar').toggleClass('d-none');
    });
});