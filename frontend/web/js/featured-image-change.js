$(function () {
    var modelName = $('.featured-image-change').data('model-name'),
        modelId   = $('.featured-image-change').data('model-id');
    var $featuredImageChange = $('.featured-image-change');
    function addPreviewImage(image, input) {
        var $container = $('<div/>').addClass('gallery-img');
        var $img       = $('<img/>').addClass('no-image blah')
                            .attr('src', image);

        var $a = $('<a/>').addClass('gallery-img__change')
                    .css('display', 'block')
                    .html(''
                        +  window.translator.get('CreateExcursionPage.changeFile')
                        + '<input type="file" class="galleryInp" '
                        + 'name="'+modelName+'" '
                        + 'accept="image/*" '
                        + '/>'
        );
        $featuredImageChange.empty();
        $img.appendTo($container);
        $container.appendTo($featuredImageChange);
        $a.appendTo($featuredImageChange);
        $(input).removeAttr('id').css('display', 'none').attr('value', $(input).val()).appendTo($featuredImageChange);
    }
    $(document).on('change', '.featured-image-change input[name="Excursion[featured_image]"]', function (event) {
        var self = this;
        if (typeof (FileReader) != "undefined") {
            var reader = new FileReader();
            reader.onload = function (e) {
                addPreviewImage(e.target.result, self);
            }
            reader.readAsDataURL($(event.target)[0].files[0]);
        } else {
            alert("This browser does not support FileReader.");
        }
    });
});
