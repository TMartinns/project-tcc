$('#data_nascimento').datepicker({
    uiLibrary: 'bootstrap4',
    locale: 'pt-br',
    format: 'dd/mm/yyyy',
    maxDate: function() {
        return new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
    }
});