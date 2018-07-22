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

    $('button.dadosPessoaisAppear').on('click', function () {
        var id = $(this).data('id');
        $('#dadosPessoais' + id).toggleClass('d-none');
        $(this).filter(function () {
            return $(this).data('id') === id;
        }).find('i').toggleClass('fa-arrow-circle-down fa-arrow-circle-up');
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
                var usuario = $('#pesquisarUsuarios').getSelectedItemData();

                var telefones = '';

                $.each(usuario.telefones, function (key, telefone) {
                    var hr = '';

                    if (key != usuario.telefones.length - 1) {
                        hr = "<hr/>"
                    }

                    telefones += "<p class='card-text'> (" + telefone.ddd + ")" + telefone.numero + "</p>" + hr;
                });

                var enderecos = '';

                $.each(usuario.enderecos, function (key, endereco) {
                    var hr = '';

                    if (key != usuario.enderecos.length - 1) {
                        hr = "<hr/>"
                    }

                    enderecos += "<p class='card-text'>" + endereco.logradouro + ", " + endereco.numero + ", " + endereco.complemento + ", " +
                        endereco.complemento + ", " + endereco.bairro +
                        "<br/>" +
                        endereco.cidade + "/" + endereco.uf +
                        "<br/>" +
                        endereco.cep +
                        "</p>" + hr;
                });

                var btnFooter = '';

                if (usuario.is_ativo == 1) {
                    btnFooter = "<a class='btn btn-outline-danger' href='/project-tcc/usuarios/desativar/" + usuario.id_pessoa + "'>Desativar</a>";
                } else {
                    btnFooter = "<a class='btn btn-outline-success' href='/project-tcc/usuarios/ativar/" + usuario.id_pessoa + "'>Ativar</a>";
                }

                var imagem = '';
                var title = '';

                if (usuario.imagem == null) {
                    title = 'Icon designed by Eucalyp from Flaticon';
                    imagem = '/project-tcc/public/img/avatars/usuarios/';
                    if (usuario.genero == 'M') {
                        imagem += 'man-' + Math.floor(Math.random() * 34);
                    } else {
                        imagem += 'woman-' + Math.floor(Math.random() * 12);
                    }
                }else {
                    imagem = '/project-tcc/public/uploads/usuarios/' + usuario.id_pessoa;
                }

                var listaUsuarios = $('#listaUsuarios');

                listaUsuarios.removeClass('row');

                listaUsuarios.html(
                    "<div class='card'>" +
                    "<div class='card-body'>" +
                    "<div class='offset-md-4 col-md-4 offset-lg-4 col-lg-4 text-center'>" +
                    "<p>" +
                    "<img class='rounded-circle avatar bg-dark' src='" + imagem + "'" +
                    "width='180' height='180' title='" + title + "'>" +
                    "</p>" +
                    "<hr/>" +
                    "<h5 class='card-title'>" + usuario.nome + "</h5>" +
                    "</div>" +
                    "<div class='row'>" +
                    "<div class='offset-md-2 col-md-4 offset-lg-2 col-lg-4'>" +
                    "<p class='card-text'><h6>Nome de usuário</h6>" + usuario.nome_usuario + "</p>" +
                    "<p class='card-text'><h6>E-mail</h6>" + usuario.email + "</p>" +
                    "<p class='card-text'><h6>Função</h6>" + usuario.funcao + "</p>" +
                    "<p class='card-text'><h6>CPF</h6>" + usuario.cpf + "</p>" +
                    "<p class='card-text'><h6>Data de nascimento</h6>" + usuario.data_nascimento + "</p>" +
                    "<p class='card-text'><h6>Nome da mãe</h6>" + usuario.nome_mae + "</p>" +
                    "</div>" +
                    "<div class='col-md-4 col-lg-4'>" +
                    "<p class='card-text'><h6>Telefones</h6>" + telefones + "</p>" +
                    "<p class='card-text'><h6>Enderecos</h6>" + enderecos + "</p>" +
                    "</div>" +
                    "</div>" +
                    "</div>" +
                    "<div class='card-footer text-center bg-white'>" + btnFooter + "</div>" +
                    "</div>" +
                    "<br/>"
                );
            }
        },
        adjustWidth: false
    });
});