function getDiligencias(diligencias) {
    var situacao = $('#relatorioDiligencias .situacao:checked').val();
    var periodo = $('#relatorioDiligencias .periodo:checked').val();

    var ajax = {
        method: 'get',
        url: '/project-tcc/diligencias/getDiligencias/' + situacao + '/' + periodo,
        success: function (resposta) {
            diligencias($.parseJSON(resposta));
        }
    };

    if (periodo == null) {
        var dataInicio = $('#relatorioDiligencias .dataInicio').val();
        var dataFim = $('#relatorioDiligencias .dataFim').val();

        var ajax = {
            method: 'post',
            url: '/project-tcc/diligencias/getDiligencias/' + situacao,
            data: {
                dataInicio: dataInicio,
                dataFim: dataFim
            },
            success: function (resposta) {
                diligencias($.parseJSON(resposta));
            }
        };
    }

    $.ajax(ajax);
};

$(function () {
    getDiligencias(function (diligencias) {
        preencherTabelaDiligencias(diligencias);
    });
});

$('#relatorioDiligencias .situacao').click(function () {
    getDiligencias(function (diligencias) {
        preencherTabelaDiligencias(diligencias);
    });
});

$('#relatorioDiligencias .periodo').click(function () {
    $('#relatorioDiligencias .dataInicio').val('');
    $('#relatorioDiligencias .dataFim').val('');

    getDiligencias(function (diligencias) {
        preencherTabelaDiligencias(diligencias);
    });
});

$('#relatorioDiligencias .dataInicio').change(function () {
    $('#relatorioDiligencias .periodo:checked').prop('checked', false);

    getDiligencias(function (diligencias) {
        preencherTabelaDiligencias(diligencias);
    });
});

$('#relatorioDiligencias .dataFim').change(function () {
    $('#relatorioDiligencias .periodo:checked').prop('checked', false);

    getDiligencias(function (diligencias) {
        preencherTabelaDiligencias(diligencias);
    });
});

function preencherTabelaDiligencias(diligencias) {
    jQuery.extend( jQuery.fn.dataTableExt.oSort, {
        "date-br-pre": function ( a ) {
            if (a == null || a == "") {
                return 0;
            }
            var ukDatea = a.split('/');
            return (ukDatea[2] + ukDatea[1] + ukDatea[0]) * 1;
        },

        "date-br-asc": function ( a, b ) {
            return ((a < b) ? -1 : ((a > b) ? 1 : 0));
        },

        "date-br-desc": function ( a, b ) {
            return ((a < b) ? 1 : ((a > b) ? -1 : 0));
        }
    } );

    $('#diligencias').DataTable({
        destroy: true,
        dom: 'Bfrtip',
        data: diligencias,
        buttons: [
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i>',
                className: 'btn btn-light bg-white shadow-sm botaoEsquerda-1',
                download: 'open',
                title: 'ADUV - Relatório de diligências'
            }
        ],
        language: {
            url: '/project-tcc/public/bower_components/DataTables/DataTables-1.10.18/lang/Portuguese-Brasil.json'
        },
        order: [[1, 'desc']],
        columnDefs: [
            { type: 'date-br', targets: 1 }
        ]
    });

    $(function () {
        $('.dt-buttons').removeClass('btn-group');
    });
};