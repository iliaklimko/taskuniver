$(function () {
    var modelName = $('.avatar-change').data('model-name'),
        modelId   = $('.avatar-change').data('model-id');
    var $avatarChange = $('.avatar-change');
    function addPreviewImage(image, input) {
        var $container = $('<div/>').addClass('user-img-wrap');
        var $innerDiv  = $('<div/>').addClass('user-img');
        var $span      = $('<span/>').addClass('user-img__close')
                            .attr('id', 'avatar-preview-remove')
                            .attr('data-model-id', modelId);
        var $div       = $('<div/>').addClass('user-img__wrapper avatar-preview')
                            .attr('id', 'avatar-preview')
                            .css('background', 'url('+image+')');
        $span.appendTo($innerDiv);
        $div.appendTo($innerDiv);

        var $a = $('<a/>').addClass('user-img__change')
                    .html(''
                        + window.translator.get('GuideEditProfilePage.chooseFileAnother')
                        + '<input type="file" class="imgInp" '
                        + 'name="'+modelName+'" '
                        + 'id="avatar-input" '
                        + 'accept="image/*" '
                        + '/>'
        );
        $avatarChange.empty();
        $innerDiv.appendTo($container);
        $a.appendTo($container);
        $container.appendTo($avatarChange);
        $(input).removeAttr('id').css('display', 'none').attr('value', $(input).val()).appendTo($avatarChange);
    }
    function addUploadButton() {
        var $container = $('<div/>').addClass('uploadbutton')
                            .attr('data-model-name', modelName)
                            .html(''
                               + '<input type="file" class="input-file" '
                               + 'name="'+modelName+'" '
                               + 'accept="image/*" '
                               + '/>'
                               + '<div class="button" >'
                               + window.translator.get('GuideEditProfilePage.chooseFile')
                               + '</div> '
                               + '<div class="input-file-text">&nbsp;</div>'
        );
        $avatarChange.empty();
        $container.appendTo($avatarChange);
    }
    $(document).on('change', '.avatar-change input[name="ProfileForm[avatar]"], .avatar-change input[name="SignupForm[avatar]"]', function (event) {
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
    $(document).on('click', '.user-img__close', function (e) {
        e.preventDefault();
        $.post('/user/remove-avatar', {
            userId: $(this).data('model-id')
        }).done(function () {
            addUploadButton();
        }.bind(this));
    });
});
