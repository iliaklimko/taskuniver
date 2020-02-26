var arr = [];
$(document).on('click', '.days', function () {
    if ($(this).prop('checked')) {
        $(this).val('Y');
    } else {
        $(this).val('N');
    }
    $('#arrDays').val(arr);

});

$(document).ready(function () {
    $('.field-arrDays').attr('style', 'display:none')
});



