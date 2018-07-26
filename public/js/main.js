$(document).ready(function () {
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

        if(botao.attr('title') == 'Expandir') {
            botao.attr('title', 'Reduzir');
        } else {
            botao.attr('title', 'Expandir');
        }

        botao.find('i').toggleClass('fa-arrow-circle-down fa-arrow-circle-up');
    });

    $('#pesquisarUsuarios').easyAutocomplete({
        url: function (url) {
            return "/project-tcc/usuarios/getUsuarios/";
        },
        getValue: function (element) {
            return element.nome;
        },
        ajaxSettings: {
            dataType: "json",
            method: "POST",
            data: {
                dataType: "json"
            }
        },
        preparePostData: function (data) {
            data.nome = $("#pesquisarUsuarios").val();
            return data;
        },
        requestDelay: 500,
        template: {
            type: "description",
            fields: {
                description: function (element) {
                    return element.nome_usuario;
                }
            }
        },
        list: {
            onClickEvent: function () {
                var resposta = $('#pesquisarUsuarios').getSelectedItemData();
                var listaUsuarios = $('#listaUsuarios');

                listaUsuarios.removeClass('row');
                listaUsuarios.html(resposta.html);
            }
        },
        adjustWidth: false
    });
});