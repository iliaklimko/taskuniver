$(document).ready(function () {
    var PostForm = {
        aboutListUrl: '/admin/post/about-list',
        formId: '#post-form input[name="Post[subjectClass]"]',
        aboutListId: '#post-form-subjectid',
        init: function () {
            this.bindEvents();
        },
        bindEvents: function () {
            $(this.formId).change(this.loadAboutList.bind(this));
        },
        loadAboutList: function (e) {
            var modelClass = $(e.target).val();
            $.ajax({
                url: this.aboutListUrl,
                type: 'get',
                data: {modelClass: modelClass}
            }).done(function (options) {
                this.populateList(this.aboutListId, options);
            }.bind(this));
        },
        populateList: function (listId, options) {
            var options = $.extend({}, options);
            $(listId).find('option').remove();
            for (var id in options) {
                var option = $('<option>')
                    .val(id)
                    .html(options[id]);
                $(listId).append(option);
            }
            $(listId).val(null);
            $(listId).trigger('change');
        }
    };
    PostForm.init();
});
