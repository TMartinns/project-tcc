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
})