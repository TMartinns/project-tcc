$('.wrap-custom-file input[type="file"]').change(function(event) {
    var $file = $(this),
        $label = $file.next('label'),
        $labelText = $label.find('span'),
        labelDefault = $labelText.text();

    var fileName = $file.val().split('\\').pop(),
        tmppath = URL.createObjectURL(event.target.files[0]);

    if (fileName) {
        $label
            .addClass('file-ok')
            .css('background-image', 'url(' + tmppath + ')');
        $labelText.text(fileName);
    } else {
        $label.removeClass('file-ok');
        $labelText.text(labelDefault);
    }
});

$('.wrap-custom-file input[type="file"]').focus(function() {
    $('.wrap-custom-file label').addClass('focus');
}).blur(function() {
    $('.wrap-custom-file label').removeClass('focus');
});