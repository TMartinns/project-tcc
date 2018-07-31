$('#sidebar').mCustomScrollbar({
    theme: "minimal-dark"
});

$('#sidebarAppear').on('click', function () {
    $('#sidebar').toggleClass('active');
    $('#container').toggleClass('sidebarActive');
});

$('#sidebar a.active').toggleClass('text-dark text-primary');

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

$('#uf').change(function () {
    var uf = $(this);

    $.ajax({
        method: "POST",
        url: uf.data('url'),
        data: {id_uf: uf.val()},
        success: function (resposta) {
            $('#cidade').empty().append("<option selected value='0'>Selecione uma cidade</option>");
            var cidades = $.parseJSON(resposta);

            $.each(cidades, function (key, cidade) {
                $('#cidade').append("<option value='" + cidade.id + "'>" + cidade.nome + "</option>");
            });
        }
    });
});