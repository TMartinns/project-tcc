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
                return element.email;
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

$('#interessado').easyAutocomplete({
    url: function (url) {
        return "/project-tcc/pessoas/getPessoas/";
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
        data.nome = $("#interessado").val();
        return data;
    },
    requestDelay: 500,
    template: {
        type: "description",
        fields: {
            description: function (element) {
                return element.cpf;
            }
        }
    },
    list: {
        onClickEvent: function () {
            var resposta = $('#interessado').getSelectedItemData();
            $('#idInteressado').val(resposta.id);
        }
    },
    adjustWidth: false,
    cssClasses: 'w-eac-100-46'
});

$('#usuarioEspecifico').easyAutocomplete({
    url: function (url) {
        return "/project-tcc/usuarios/getUsuariosAtivos/";
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
        data.nome = $("#usuarioEspecifico").val();
        return data;
    },
    requestDelay: 500,
    template: {
        type: "description",
        fields: {
            description: function (element) {
                return element.funcao;
            }
        }
    },
    list: {
        onClickEvent: function () {
            var resposta = $('#usuarioEspecifico').getSelectedItemData();
            $('#idUsuarioEspecifico').val(resposta.id);
        }
    },
    adjustWidth: false
});