$(function () {
    $(document).on('change', '.popup__form .input-file', function (event) {
        var file = this.files[0];
        var value = this.value;
        if(file) {
            $('.remove').remove();
            $('.button').remove();
            value = value.replace(/C:\\fakepath\\/i, '');
            value = value.length > 35
                ? value.substring(0, 15) + '...' + value.substring(value.length-10)
                : value;
            value +=' ('
                    + Number(file.size/1024/1024).toPrecision(2)
                    + 'Mb)'
            $(this).siblings('.input-file-text')
                .html(value)
                .after("<a href='#' class='remove'></a>").addClass('active');
        } else {
            $(this).siblings('.input-file-text').html('').removeClass('active');
            $('.remove').remove();
        }
    });
    $(document).on( 'click', '.popup__form .remove', function(){
        var $inputFile = $(this).parent().find('.input-file');
        var inputFileLabel = $inputFile.data('label');
        $inputFile.remove();
        $(this).parent().prepend('<input class="input-file" type="file" name="'
            + $(this).parent().data('model-name')
            + '"'
            + (inputFileLabel ? ' data-label="'+inputFileLabel+'"' : '')
            + '>')
        $('.input-file-text').html('');
        $('.remove').remove();
        $('.input-file-text').html('').removeClass('active');
        $('.input-file').after('<div class="button" >'
            + (inputFileLabel ? window.translator.get('SubmitPostPopup.input')+'<span>'+inputFileLabel+'</span>' : window.translator.get('SubmitPostPopup.input'))
            + '</div>');
        return false;
    });
});
