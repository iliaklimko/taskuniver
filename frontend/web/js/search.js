$(function () {
    var $autoContainer = $('.page-header__autocomplete');

    //AUTOCOMPLETE
    $('.page-header__input, .index-search__input').keyup(function() {
        var qString = $(this).val().trim();
        if (qString.length >= 3) {
            $.get(searchUrl, {q: qString})
             .done(function (data) {
                loadAutocomplete(data, $autoContainer);
                if (data.length > 0) {
                    $autoContainer.fadeIn();
                } else {
                    $autoContainer.fadeOut();
                }
             });
        } else {
            loadAutocomplete([], $autoContainer);
            $autoContainer.fadeOut();
        }
    });
    //CLOSE AUTOCOMPLETE
    $(document).click(function (e){
        if (!$autoContainer.is(e.target) && $autoContainer.has(e.target).length === 0) {
            $autoContainer.fadeOut(200);
        }
    });

    function loadAutocomplete(data, $container) {
        function renderA(props) {
            return '<a href="' + props.href + '">' + props.text + '</a>';
        }
        function renderLi(props) {
            return '<li class="page-header__autocomplete-item">' + renderA(props) + '</li>';
        }
        function render(items) {
            // return '<ul>' + items.map(renderLi).join('') + '</ul>';
            return items.map(renderLi).join('');
        }
        $container.html(render(data));
    }
});
