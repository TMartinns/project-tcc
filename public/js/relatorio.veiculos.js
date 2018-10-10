function getVeiculos(veiculos) {
    var periodo = $('#relatorioVeiculos .periodo:checked').val();

    var ajax = {
        method: 'get',
        url: '/project-tcc/veiculos/getVeiculosUtilizados/' + periodo,
        success: function (resposta) {
            veiculos($.parseJSON(resposta));
        }
    };

    if (periodo == null) {
        var dataInicio = $('#relatorioVeiculos .dataInicio').val();
        var dataFim = $('#relatorioVeiculos .dataFim').val();

        var ajax = {
            method: 'post',
            url: '/project-tcc/veiculos/getVeiculosUtilizados',
            data: {
                dataInicio: dataInicio,
                dataFim: dataFim
            },
            success: function (resposta) {
                veiculos($.parseJSON(resposta));
            }
        };
    }

    $.ajax(ajax);
};

$(function () {
    getVeiculos(function (veiculos) {
        preencherTabelaVeiculos(veiculos);
    });
});

$('#relatorioVeiculos .situacao').click(function () {
    getVeiculos(function (veiculos) {
        preencherTabelaVeiculos(veiculos);
    });
});

$('#relatorioVeiculos .periodo').click(function () {
    $('#relatorioVeiculos .dataInicio').val('');
    $('#relatorioVeiculos .dataFim').val('');

    getVeiculos(function (veiculos) {
        preencherTabelaVeiculos(veiculos);
    });
});

$('#relatorioVeiculos .dataInicio').change(function () {
    $('#relatorioVeiculos .periodo:checked').prop('checked', false);

    getVeiculos(function (veiculos) {
        preencherTabelaVeiculos(veiculos);
    });
});

$('#relatorioVeiculos .dataFim').change(function () {
    $('#relatorioVeiculos .periodo:checked').prop('checked', false);

    getVeiculos(function (veiculos) {
        preencherTabelaVeiculos(veiculos);
    });
});

function preencherTabelaVeiculos(veiculos) {
    jQuery.extend( jQuery.fn.dataTableExt.oSort, {
        "date-br-pre": function ( a ) {
            var x;

            if ( $.trim(a) !== '' ) {
                var frDatea = $.trim(a).split(' ');
                var frTimea = (undefined != frDatea[1]) ? frDatea[1].split(':') : [00,00,00];
                var frDatea2 = frDatea[0].split('/');
                x = (frDatea2[2] + frDatea2[1] + frDatea2[0] + frTimea[0] + frTimea[1] + ((undefined != frTimea[2]) ? frTimea[2] : 0)) * 1;
            }
            else {
                x = Infinity;
            }

            return x;
        },

        "date-br-asc": function ( a, b ) {
            return a - b;
        },

        "date-br-desc": function ( a, b ) {
            return b - a;
        }
    } );

    $('#veiculos').DataTable({
        destroy: true,
        dom: 'Bfrtip',
        data: veiculos,
        buttons: [
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i>',
                className: 'btn btn-light bg-white shadow-sm botaoEsquerda',
                download: 'open',
                title: 'ADUV - Relatório de veículos'
            }
        ],
        language: {
            url: '/project-tcc/public/bower_components/DataTables/DataTables-1.10.18/lang/Portuguese-Brasil.json'
        },
        order: [[0, 'desc']],
        columnDefs: [
            { type: 'date-br', targets: 0 }
        ]
    });

    $(function () {
        $('.dt-buttons').removeClass('btn-group');
    });
};