$('.modalSeguro').modal({
    backdrop: 'static',
    keyboard: false,
    show: false
});

$('#modalEditarVeiculos').on('show.bs.modal', function (event) {
    var veiculo = $(event.relatedTarget).data('veiculo');
    var modal = $(this);

    modal.find('#modelo').val(veiculo.modelo);
    modal.find('#marca').val(veiculo.marca);
    modal.find('#cor').val(veiculo.cor);
    modal.find('#renavam').val(veiculo.renavam);
    modal.find('#ano').val(veiculo.ano);
    modal.find('#placa').val(veiculo.placa);
    modal.find('.modal-content form').attr('action', VEICULOS + 'editar/' + veiculo.id);

    if (veiculo.imagem != null) {
        modal.find('.wrap-custom-file label').addClass('file-ok')
            .css('background-image', 'url(' + UPLOADS + 'veiculos/' + veiculo.id + '/' + veiculo.imagem + ')');
    } else {
        modal.find('.wrap-custom-file label').removeClass('file-ok')
            .css('background-image', '');
        modal.find('.wrap-custom-file span').text('Imagem do veículo');
    }
});

$('#modalNovoInteressado').find('#botaoCadastrar').click(function () {
    var modal = $('#modalNovoInteressado');

    var post = {
        nome: modal.find('#nome').val(),
        cpf: modal.find('#cpf').val(),
        dataNascimento: modal.find('.dataNascimento').val(),
        ddd: modal.find('#ddd').val(),
        numeroTelefone: modal.find('#numeroTelefone').val(),
        logradouro: modal.find('#logradouro').val(),
        numeroEndereco: modal.find('#numeroEndereco').val(),
        complemento: modal.find('#complemento').val(),
        cep: modal.find('#cep').val(),
        bairro: modal.find('#bairro').val(),
        cidade: modal.find('#cidade').val()
    };

    $.ajax({
        method: "POST",
        url: PESSOAS + 'cadastrar',
        data: post,
        success: function (resposta) {
            var resposta = $.parseJSON(resposta);

            if (resposta.status == true) {
                $('#cadastrarDiligencias #interessado').val(resposta.pessoa.nome);
                $('#idInteressado').val(resposta.pessoa.id);

                modal.modal('hide');
            } else {
                modal.find('#alertBody').text('');

                if (modal.find('.alert').hasClass('d-none')) {
                    modal.find('.alert').removeClass('d-none');
                }
                $.each(resposta.errors, function (key, error) {
                    modal.find('#alertBody').append(error + "<br/>");
                });
            }
        }
    });
});

$('#modalNovoInteressado').on('hide.bs.modal', function () {
    var modal = $(this);

    if (!modal.find('.alert').hasClass('d-none')) {
        modal.find('.alert').addClass('d-none');
    }

    modal.find('#nome').val('');
    modal.find('#cpf').val('');
    modal.find('.dataNascimento').val('');
    modal.find('#ddd').val('');
    modal.find('#numeroTelefone').val('');
    modal.find('#logradouro').val('');
    modal.find('#numeroEndereco').val('');
    modal.find('#complemento').val('');
    modal.find('#cep').val('');
    modal.find('#bairro').val('');
    modal.find('#cidade').empty().append("<option selected value='0'>Selecione um estado antes</option>");
    modal.find('#uf').val(modal.find('#uf option:first').val());
});

$('#modalEditarInteressado').on('show.bs.modal', function (event) {
    var interessado = $(event.relatedTarget).data('interessado');
    var modal = $(this);

    $.ajax({
        method: "GET",
        url: PESSOAS + 'getPessoa/' + $('#idInteressado').val(),
        success: function (resposta) {
            var resposta = $.parseJSON(resposta);


            modal.find('#nome').val(resposta.nome);
            modal.find('#cpf').val(resposta.cpf);
            modal.find('.dataNascimento').val(resposta.dataNascimento);
            modal.find('#ddd').val(resposta.telefone.ddd);
            modal.find('#numeroTelefone').val(resposta.telefone.numero);
            modal.find('#logradouro').val(resposta.endereco.logradouro);
            modal.find('#numeroEndereco').val(resposta.endereco.numero);
            modal.find('#complemento').val(resposta.endereco.complemento);
            modal.find('#cep').val(resposta.endereco.cep);
            modal.find('#bairro').val(resposta.endereco.bairro);
            modal.find('#uf').find('option[value="' + resposta.endereco.idUf + '"]').prop('selected', true);
            modal.find('#cidade').html(
                '<option value="' + resposta.endereco.idCidade + '" selected>' + resposta.endereco.cidade + '</option>'
            );
        }
    });
});

$('#modalEditarInteressado').on('hide.bs.modal', function () {
    var modal = $(this);

    if (!modal.find('.alert').hasClass('d-none')) {
        modal.find('.alert').addClass('d-none');
    }

    modal.find('#uf').find('option:selected').prop('selected', false);
});

$('#modalEditarInteressado').find('#botaoCadastrar').click(function () {
    var modal = $('#modalEditarInteressado');

    var post = {
        nome: modal.find('#nome').val(),
        cpf: modal.find('#cpf').val(),
        dataNascimento: modal.find('.dataNascimento').val(),
        ddd: modal.find('#ddd').val(),
        numeroTelefone: modal.find('#numeroTelefone').val(),
        logradouro: modal.find('#logradouro').val(),
        numeroEndereco: modal.find('#numeroEndereco').val(),
        complemento: modal.find('#complemento').val(),
        cep: modal.find('#cep').val(),
        bairro: modal.find('#bairro').val(),
        cidade: modal.find('#cidade').val()
    };

    $.ajax({
        method: "POST",
        url: PESSOAS + 'editar/' + $('#idInteressado').val(),
        data: post,
        success: function (resposta) {
            var resposta = $.parseJSON(resposta);

            if (resposta.status == true) {
                $('#cadastrarDiligencias #interessado').val(resposta.pessoa.nome);
                $('#idInteressado').val(resposta.pessoa.id);

                modal.modal('hide');
            } else {
                modal.find('#alertBody').text('');

                if (modal.find('.alert').hasClass('d-none')) {
                    modal.find('.alert').removeClass('d-none');
                }
                $.each(resposta.errors, function (key, error) {
                    modal.find('#alertBody').append(error + "<br/>");
                });
            }
        }
    });
});

$('#modalDadosDiligencia').on('show.bs.modal', function (event) {
    var diligencia = $(event.relatedTarget).data('diligencia');
    var modal = $(this);

    modal.find('.modal-title').html(diligencia.numeroProtocolo);
    modal.find('#promotoria').html("<h6>Promotoria</h6>" + diligencia.promotoria);
    modal.find('#descricao').html("<h6>Descrição</h6>" + diligencia.descricao);
    modal.find('#interessado').html("<h6>Interessado</h6>" + diligencia.interessado);
    modal.find('#tipoDiligencia').html("<h6>Tipo de diligência</h6>" + diligencia.tipoDiligencia);
    modal.find('#prazoCumprimento').html("<h6>Prazo para cumprimento</h6>" + diligencia.prazoCumprimento);
});

$('#modalEventos').on('show.bs.modal', function (event) {
    jQuery.extend(jQuery.fn.dataTableExt.oSort, {
        "date-br-pre": function (a) {
            var x;

            if ($.trim(a) !== '') {
                var frDatea = $.trim(a).split(' ');
                var frTimea = (undefined != frDatea[1]) ? frDatea[1].split(':') : [00, 00, 00];
                var frDatea2 = frDatea[0].split('/');
                x = (frDatea2[2] + frDatea2[1] + frDatea2[0] + frTimea[0] + frTimea[1] + ((undefined != frTimea[2]) ? frTimea[2] : 0)) * 1;
            }
            else {
                x = Infinity;
            }

            return x;
        },

        "date-br-asc": function (a, b) {
            return a - b;
        },

        "date-br-desc": function (a, b) {
            return b - a;
        }
    });

    var eventos = $(event.relatedTarget).data('eventos');
    var modal = $(this);

    modal.find('#eventos').DataTable({
        destroy: true,
        data: eventos,
        language: {
            url: BOWER + 'DataTables/DataTables-1.10.18/lang/Portuguese-Brasil.json'
        },
        order: [[0, 'desc']],
        columnDefs: [
            {type: 'date-br', targets: 0}
        ]
    });
});

$('#modalRegistrosUtilizacao').on('show.bs.modal', function (event) {
    jQuery.extend(jQuery.fn.dataTableExt.oSort, {
        "date-br-pre": function (a) {
            var x;

            if ($.trim(a) !== '') {
                var frDatea = $.trim(a).split(' ');
                var frTimea = (undefined != frDatea[1]) ? frDatea[1].split(':') : [00, 00, 00];
                var frDatea2 = frDatea[0].split('/');
                x = (frDatea2[2] + frDatea2[1] + frDatea2[0] + frTimea[0] + frTimea[1] + ((undefined != frTimea[2]) ? frTimea[2] : 0)) * 1;
            }
            else {
                x = Infinity;
            }

            return x;
        },

        "date-br-asc": function (a, b) {
            return a - b;
        },

        "date-br-desc": function (a, b) {
            return b - a;
        }
    });

    var registros = $(event.relatedTarget).data('registros');
    var modal = $(this);

    modal.find('#registros').DataTable({
        destroy: true,
        data: registros,
        language: {
            url: BOWER + 'DataTables/DataTables-1.10.18/lang/Portuguese-Brasil.json'
        },
        order: [[0, 'desc']],
        columnDefs: [
            {type: 'date-br', targets: 0}
        ]
    });
});

$('#modalQrCode').on('show.bs.modal', function (event) {
    var modelo = $(event.relatedTarget).data('modelo');
    var modal = $(this);

    var chl = $(event.relatedTarget).data('id');
    var chs = "150x150";
    var cht = "qr";

    var url = "https://chart.googleapis.com/chart?chs=" + chs + "&cht=" + cht + "&chl=" + chl;

    modal.find('.modal-body img').attr('src', url);
    modal.find('.modal-body small').html("O QR Code do veículo <strong>" + modelo + "</strong> foi gerado com sucesso!");
});

var scanner = null;

$('#modalRegistrarUtilizacao').on('show.bs.modal', function () {
    var modal = $(this);

    scanner = new Instascan.Scanner({
        video: document.getElementById('qrScanner')
    });

    scanner.addListener('scan', function (content) {
        $.ajax({
            method: 'GET',
            url: VEICULOS + 'getVeiculo/' + content,
            success: function (resposta) {
                var veiculo = $.parseJSON(resposta);

                if (veiculo != "") {
                    modal.modal('hide');

                    modal = $('#modalConfirmaRegistro').modal('show');

                    modal.find('#veiculo').html(veiculo.modelo)
                        .tooltip();
                    modal.find('button#confirmaRegistro').attr('data-veiculo', veiculo.id);

                    var title = '';
                    var src = UPLOADS + 'veiculos/' + veiculo.id + '/' + veiculo.imagem;

                    if (veiculo.imagem == null) {
                        title = 'Icon designed by Freepik from Flaticon';
                        var avatar = 'car-' + Math.floor(Math.random() * 8);
                        src = IMG + 'avatars/veiculos/' + avatar;
                    }

                    if (veiculo.utilizado) {
                        modal.find('.ocorrencia').removeClass('d-none');
                    }

                    modal.find('.modal-body img').attr('src', src)
                        .tooltip().attr('data-original-title', title);
                } else {
                    modal.find('.modal-footer small').removeClass('text-muted')
                        .addClass('text-danger')
                        .text('Veículo desativado ou inexistente! ' +
                            'Por favor, contate a coordenadoria para obter mais informações.');

                    setTimeout(function () {
                        modal.find('.modal-footer small').removeClass('text-danger')
                            .addClass('text-muted')
                            .text('Posicione o QR Code próximo da câmera para que ele possa ser lido.');
                    }, 10 * 1000);
                }
            }
        });
    });

    Instascan.Camera.getCameras().then(function (cameras) {
        if (cameras.length > 0) {
            scanner.start(cameras[0]);
        } else {
            console.error('No cameras found.');
        }
    }).catch(function (e) {
        console.error(e);
    });
});

$('#modalRegistrarUtilizacao').on('hide.bs.modal', function () {
    scanner.stop();

    scanner = null;

    $(this).find('.modal-footer small').removeClass('text-danger')
        .text('Posicione o QR Code próximo da câmera para que ele possa ser lido.');
});

$('#modalConfirmaRegistro').find('button#confirmaRegistro').click(function () {
    var modal = $('#modalConfirmaRegistro');
    var veiculo = $(this).data('veiculo');
    var ocorrencia = modal.find('#ocorrencia').val();

    $.ajax({
        method: "POST",
        url: VEICULOS + 'registrarUsoVeiculo/' + veiculo,
        data: {ocorrencia: ocorrencia},
        success: function (resposta) {
            modal.find('.modal-footer div').first().removeClass('d-none');
            modal.find('.modal-footer div').last().addClass('d-none');

            if (!modal.find('.ocorrencia').hasClass('d-none'))
                modal.find('.ocorrencia').addClass('d-none');
        }
    });
});

$('#modalConfirmaRegistro').on('hide.bs.modal', function () {
    var modal = $(this);

    modal.find('#veiculo').html('');
    modal.find('button#confirmaRegistro').attr('data-veiculo', '')
        .removeData('veiculo');
    modal.find('.modal-body img').attr('src', '')
        .attr('data-original-title', '');
    modal.find('.modal-footer div').first().addClass('d-none');
    modal.find('.modal-footer div').last().removeClass('d-none');

    if (!modal.find('.ocorrencia').hasClass('d-none'))
        modal.find('.ocorrencia').addClass('d-none');
});

$('#modalInteressado').on('show.bs.modal', function (event) {
    var id = $(event.relatedTarget).data('id');
    var modal = $(this);

    $.ajax({
        method: 'GET',
        url: PESSOAS + 'getPessoa/' + id,
        success: function (resposta) {
            var pessoa = $.parseJSON(resposta);

            modal.find('.modal-title').html(pessoa.nome);
            modal.find('#cpf').html('<h6>CPF</h6>' + pessoa.cpf);
            modal.find('#dataNascimento').html('<h6>Data de Nascimento</h6>' + pessoa.dataNascimento);
            modal.find('#telefone').html('<h6>Telefone</h6>');
            if (pessoa.telefone != null) {
                var telefone = pessoa.telefone;
                modal.find('#telefone').html('<h6>Telefone</h6> (' + telefone.ddd + ') ' + telefone.numero);
            }

            modal.find('#endereco').html('<h6>Endereço</h6>');
            if (pessoa.endereco != null) {
                var endereco = pessoa.endereco;
                modal.find('#endereco').html('<h6>Endereço</h6>' +
                    endereco.logradouro + ', ' + endereco.numero + ', ' + endereco.complemento + ', ' + endereco.bairro +
                    '<br/>' +
                    endereco.cidade + '/' + endereco.uf +
                    '<br/>' +
                    endereco.cep
                );
            }
        }
    });
});