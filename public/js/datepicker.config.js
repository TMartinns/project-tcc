$('.dataNascimento').datepicker({
    uiLibrary: 'bootstrap4',
    header: true,
    iconsLibrary: 'fontawesome',
    locale: 'pt-br',
    format: 'dd/mm/yyyy',
    maxDate: function() {
        return new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
    }
});

$('#cadastrarDiligencias #prazoCumprimento').datepicker({
    uiLibrary: 'bootstrap4',
    header: true,
    iconsLibrary: 'fontawesome',
    locale: 'pt-br',
    format: 'dd/mm/yyyy',
    minDate: function() {
        return new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
    }
});