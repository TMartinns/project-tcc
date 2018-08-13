$('#modalEditarVeiculos').on('show.bs.modal', function (event) {
    var veiculo = $(event.relatedTarget).data('veiculo');
    var modal = $(this);

    modal.find('#modelo').val(veiculo.modelo);
    modal.find('#marca').val(veiculo.marca);
    modal.find('#cor').val(veiculo.cor);
    modal.find('#renavam').val(veiculo.renavam);
    modal.find('#ano').val(veiculo.ano);
    modal.find('#placa').val(veiculo.placa);
    modal.find('.modal-content form').attr('action', veiculo.action + '/' + veiculo.id);
});

$('#modalNovoInteressado').find('#botaoCadastrar').click(function () {
    var modal = $('#modalNovoInteressado');

    var post = {
        nome: modal.find('#nome').val(),
        cpf: modal.find('#cpf').val(),
        data_nascimento: modal.find('#dataNascimento').val(),
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
        url: $(this).data('action'),
        data: post,
        success: function (resposta) {
            var resposta = $.parseJSON(resposta);

            if (resposta.status == true) {
                $('#interessado').val(resposta.pessoa.nome);
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
    modal.find('#dataNascimento').val('');
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

$('#modalDadosDiligencia').on('show.bs.modal', function (event) {
    var diligencia = $(event.relatedTarget).data('diligencia');
    var modal = $(this);

    modal.find('.modal-title').html("Diligência " + diligencia.numeroProtocolo);
    modal.find('#promotoria').html("<h6>Promotoria</h6>" + diligencia.promotoria);
    modal.find('#descricao').html("<h6>Descrição</h6>" + diligencia.descricao);
    modal.find('#interessado').html("<h6>Interessado(a)</h6>" + diligencia.interessado);
    modal.find('#tipoDiligencia').html("<h6>Tipo de diligência</h6>" + diligencia.tipoDiligencia);
    modal.find('#prazoCumprimento').html("<h6>Prazo para cumprimento</h6>" + diligencia.prazoCumprimento);
});