$('#sidebar').mCustomScrollbar({
    theme: "minimal-dark"
});

$('#sidebarAppear').on('click', function () {
    $('#sidebar').toggleClass('active');
    $('#container').toggleClass('sidebarActive');
});

$('#sidebar a.active').toggleClass('text-dark text-primary');

if (window.matchMedia('(max-width: 425px)').matches) {
    $('div#pesquisar').toggleClass('d-none');
    $('#sidebarItens').prepend(
        "<li class='nav-item'>" +
        "<div id='pesquisar' class='input-group'>" +
        $('div#pesquisar').html() +
        "</div>" +
        "</li>"
    );
}

$('button.dadosPessoaisExpandir').on('click', function () {
    var id = $(this).data('id');
    $('#dadosPessoais' + id).toggleClass('d-none');

    var botao = $(this).filter(function () {
        return $(this).data('id') === id;
    });

    if (botao.attr('title') == 'Expandir') {
        botao.attr('title', 'Reduzir');
    } else {
        botao.attr('title', 'Expandir');
    }

    botao.find('i').toggleClass('fa-arrow-circle-down fa-arrow-circle-up');
});