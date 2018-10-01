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

function pesquisarAutocomplete() {
    $('#pesquisar').easyAutocomplete({
        url: function (url) {
            return "/project-tcc/pesquisas/getResultado/";
        },
        getValue: function (element) {
            return element.texto;
        },
        ajaxSettings: {
            dataType: "json",
            method: "POST",
            data: {
                dataType: "json"
            }
        },
        preparePostData: function (data) {
            data.info = $('#pesquisar').val();
            return data;
        },
        requestDelay: 500,
        template: {
            type: "custom",
            method: function(value, item) {
                return "<a href='" + item.url + "'>" + item.texto + "</a>";
            }
        },
        categories: [
            {
                listLocation: "usuarios",
                header: "Usuários"
            },
            {
                listLocation: "diligencias",
                header: "Diligências"
            },
            {
                listLocation: "veiculos",
                header: "Veículos"

            }
        ],
        adjustWidth: false
    });
}