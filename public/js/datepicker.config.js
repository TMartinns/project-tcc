var maxDateToday = {
    uiLibrary: 'bootstrap4',
    header: true,
    iconsLibrary: 'fontawesome',
    locale: 'pt-br',
    format: 'dd/mm/yyyy',
    maxDate: function() {
        return new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
    }
};

var minDateToday = {
    uiLibrary: 'bootstrap4',
    header: true,
    iconsLibrary: 'fontawesome',
    locale: 'pt-br',
    format: 'dd/mm/yyyy',
    minDate: function() {
        return new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
    }
};

$('#modalEditarPerfil .dataNascimento').datepicker(maxDateToday);

$('#modalNovoInteressado .dataNascimento').datepicker(maxDateToday);

$('#modalEditarInteressado .dataNascimento').datepicker(maxDateToday);

$('.prazoCumprimento').datepicker(minDateToday);

$('#diligencia.dataInicio').datepicker(maxDateToday);

$('#diligencia.dataFim').datepicker(maxDateToday);

$('#veiculo.dataInicio').datepicker(maxDateToday);

$('#veiculo.dataFim').datepicker(maxDateToday);

