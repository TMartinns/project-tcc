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

$('.dataNascimento').datepicker(maxDateToday);

$('.prazoCumprimento').datepicker(minDateToday);

$('.dataInicio').datepicker(maxDateToday);

$('.dataFim').datepicker(maxDateToday);

