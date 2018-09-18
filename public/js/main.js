$('#sidebar').mCustomScrollbar({
    theme: "minimal-dark"
});

$('#sidebarAppear').on('click', function () {
    $('#sidebar').toggleClass('active');
    $('#container').toggleClass('sidebarActive');
});

$('#enviarDiligencias #opcaoEspecifico').click(function () {
    $('#enviarDiligencias .usuarioEspecifico').removeClass('d-none');
});

$('#enviarDiligencias #opcaoOficial').click(function () {
    $('#enviarDiligencias .usuarioEspecifico').addClass('d-none');
});

$('.tooltipTitle').tooltip();

$(function () {
    if (window.matchMedia('(min-width: 769px)').matches) {
        $('#sidebar').addClass('active');
        $('#container').addClass('sidebarActive');
    }

    if (window.matchMedia('(max-width: 379px)').matches) {
        $('#brand').attr('src', '/project-tcc/public/img/favicon.png');
    }
});

$('.dadosPessoaisExpandir').on('click', function () {
    var id = $(this).data('id');
    $('#dadosPessoais' + id).toggleClass('d-none');

    var botao = $(this).filter(function () {
        return $(this).data('id') === id;
    });

    botao.find('i').toggleClass('fa-arrow-circle-down fa-arrow-circle-up');
});

$('.dadosVeiculosExpandir').on('click', function () {
    var id = $(this).data('id');
    $('#dadosVeiculos' + id).toggleClass('d-none');

    var botao = $(this).filter(function () {
        return $(this).data('id') === id;
    });

    botao.find('i').toggleClass('fa-arrow-circle-down fa-arrow-circle-up');
});

$('.diligenciasExpandir').on('click', function () {
    var id = $(this).data('id');
    $('#diligencias' + id).toggleClass('d-none');

    var botao = $(this).filter(function () {
        return $(this).data('id') === id;
    });

    botao.find('i').toggleClass('fa-arrow-circle-down fa-arrow-circle-up');
});

$('#uf').change(function () {
    var uf = $(this);

    $.ajax({
        method: 'get',
        url: uf.data('url') + '/' + uf.val(),
        success: function (resposta) {
            $('#cidade').empty().append("<option selected value='0'>Selecione uma cidade</option>");
            var cidades = $.parseJSON(resposta);

            $.each(cidades, function (key, cidade) {
                $('#cidade').append("<option value='" + cidade.id + "'>" + cidade.nome + "</option>");
            });
        }
    });
});

$(function () {
    $.ajax({
        url: '/project-tcc/usuarios/getUsuarioFuncao/',
        success: function(resposta) {
            var funcao = $.parseJSON(resposta);

            if(funcao == 'O') {
                $('#menu li').last().find('.nav-link').attr('data-toggle', 'modal')
                    .attr('data-target', '#modalRegistraUsoVeiculo');
            }
        }
    });

    $('#menu').prepend("<li class='nav-item mb-4 mt-1'>" +
        "<input type='text' class='form-control ml-3 shadow' id='pesquisar' placeholder='Pesquisar...'>");

    pesquisarAutocomplete();
});

$(function() {
    var interval = 0;

    $('.cancelar')
        .mouseover(function () {
            var botao = $(this);
            interval = setInterval(function () {
                botao.removeClass('disabled');
                botao.attr('data-dismiss', 'modal');
            }, 3000);
        })
        .mouseout(function () {
            var botao = $(this);
            clearInterval(interval);
            botao.addClass('disabled');
            botao.attr('data-dismiss', '');
        });
});

$('.notificacaoMensagem').click(function () {
    var id = $(this).data('id');

    $.ajax({
        method: 'get',
        url: '/project-tcc/notificacoes/visto/' + id
    });
});