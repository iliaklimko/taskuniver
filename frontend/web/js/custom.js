$(function () {
    var fancyHash = location.hash;
    function scrollToSearchInput() {
        var id  = $(this).attr('href'),
            top = $(id).offset().top;
        $('body,html').animate({scrollTop: top}, 1500);
    }
    if (fancyHash === 'index-search') {
        scrollToSearchInput();
    }
    if (fancyHash) {
        $.fancybox.open($('a[href="'+fancyHash+'"]'), {
            openEffect  : 'fade',
            closeEffect : 'fade',
            maxWidth: 680,
            padding:0,
            beforeShow: function(){
                $("body").css({'overflow-y':'hidden'});
                if($(window).width() > 700){
                    $("html").css({'padding-right':'17px'});
                }
                if($(window).width() < 700){
                    $("body").css({'position': 'fixed'});
                }
            },
            afterClose: function(){
                if (fancyHash == '#signup-confirmed' || fancyHash == '#password-saved') {
                    $('[href="#guide-enter"]').eq(0).trigger('click');
                } else {
                    window.location = window.location.href.split('#')[0];
                    $("body").css({'overflow-y':'auto'});
                    if($(window).width() > 700){
                        $("html").css({'padding-right':'0'});
                    }
                    if($(window).width() < 700){
                        $("body").css({'position': 'static'});
                    }
                }
            },
            helpers : {
                overlay : {
                    locked: false,
                }
            }
        });
    }

    /** scroll to error */
    var $firstErrorDivs = $('.input-wrap--error');
    if ($firstErrorDivs.length) {
        $('html, body').stop().animate({
            scrollTop: $firstErrorDivs.first().offset().top
        }, 1000);
    }
    /** *************** */

    /** excursion top-filter-form */
    $('[name="order_by"]').change(function () {
        $('#top-filter-form').submit();
    });

    $('#exursions-filter-reset').click(function (e) {
        e.preventDefault();
        window.location = $(this).data('reset-url');
    });

    function hideErrors($input)
    {
        $input.parent().find('.label-error').empty();
        $input.parent().parent().removeClass('input-wrap--error');
    }
    $('.input-wrap--error textarea, .input-wrap--error input')
        .focus(function () {
            hideErrors($(this));
        }
    );
    $('.input-wrap--error select')
        .change(function () {
            hideErrors($(this));
        }
    );
    $('.checkbox-wrap [type="checkbox"]')
        .change(function () {
            $(this).parent().find('.label-error').empty();
        }
    );

    $('#login-form').on('ajaxComplete', function (e) {
        var $usernameError = $('#username-field-login-form + .label-error');
        if ($usernameError.length) {
            if ($usernameError.html() == 'Регистрация не подтверждена') {
                $('#confirm-retry-login-form').attr('data-email', $('#username-field-login-form').val())
                $('#confirm-wrap-hide').hide();
                $('#confirm-wrap-show').show();
                $('#username-field-login-form').keyup(function (e) {
                    if (!$(this).val().trim()) {
                        $('#confirm-wrap-hide').show();
                        $('#confirm-wrap-show').hide();
                        $('#username-field-login-form').val('');
                        $('#username-field-login-form + .label-error').empty();
                    }
                });
            } else {
                $('#confirm-wrap-hide').show();
                $('#confirm-wrap-show').hide();
            }
            $.fancybox.update();
        }
    });
    $('#recovery-form').on('ajaxComplete', function (e) {
        var $usernameError = $('#email-field-recovery-form + .label-error');
        if ($usernameError.length) {
            if ($usernameError.html() == 'Регистрация не подтверждена') {
                $('#confirm-retry-recovery-form').attr('data-email', $('#email-field-recovery-form').val())
                $('#recovery-btn-wrap').hide();
                $('#confirm-retry-wrap').show();
                $('#email-field-recovery-form').keyup(function (e) {
                    if (!$(this).val().trim()) {
                        $('#recovery-btn-wrap').show();
                        $('#confirm-retry-wrap').hide();
                        $('#email-field-recovery-form').val('');
                        $('#email-field-recovery-form + .label-error').empty();
                    }
                });
            } else {
                $('#recovery-btn-wrap').show();
                $('#confirm-retry-wrap').hide();
            }
            $.fancybox.update();
        }
    });
    $('#confirm-retry-login-form, #confirm-retry-recovery-form').on('click', function () {
        window.location = $(this).data('url')+'?email='+$(this).data('email');
    });

    if ($('.time-mask').length>0) {
        $('.time-mask').inputmask("99:99");
    };

    $(document).on('change', '#add-image-input', function () {
        var output = $("#result");
        var imageInput = this;
        if (typeof (FileReader) != "undefined") {
            if (imageInput.files && imageInput.files[0]) {
                var reader = new FileReader();
                reader.onload = function(event) {
                    var picFile = event.target;
                    var inputName = $(imageInput).data('name');
                    var div = $('<div>').attr('class', 'photo-list__item');
                    $(imageInput)
                        .removeAttr('id')
                        .attr('name', inputName)
                        .css('display', 'none')
                        .attr('value', $(imageInput).val())
                        .appendTo(div);
                    div.append(
                        '<img class="thumbnail" src="'
                        + picFile.result
                        + '"/><a href="#" class="del"></a>'
                    );
                    output.append(div);

                    var newIinput = $('<input>')
                        .attr('type', 'file')
                        .attr('data-name', inputName)
                        .attr('id', 'add-image-input')
                        .appendTo('#add-image-wrap');

                    $('#add-image-wrap .photo-list__add').html(window.translator.get('CreateExcursionPage.addFileMore'));
                };
                reader.readAsDataURL(imageInput.files[0]);
            }
        } else {
            console.log("Your browser does not support File API");
        }
    });

    $(document).on('click', '#result .del', function (e) {
        e.preventDefault();
        $.post($(this).attr('href'))
         .done(function () {
            var item = $(this).parents('.photo-list__item');
            item.remove();
            if ($('#result .photo-list__item').length == 0) {
                $('#add-image-wrap .photo-list__add').html(window.translator.get('GuideEditProfilePage.chooseFile'));
            }
        }.bind(this));
    });

    // currency changer
    $('#currency-changer-form').change(function () {
        $(this).submit();
    });

    // language changer form
    $('#language-changer-form').change(function () {
        var url = $(this).attr('action');
        window.location = url;
    });
});

var arr = [];
$(document).on('click', '.days', function () {
    if ($(this).prop('checked')) {
        $(this).val('Y');
    } else {
        $(this).val('N');
    }
    $('#arrDays').val(arr);

});

$(document).on('click','.btn--minimal',function () {

    if ($('.days').is(':checked') || $('#one_time_excursion').is(':checked')){
        $('#arrDays').val('Y');
    } else {
        $('#arrDays').val('');
    }
})

$(document).ready(function () {
   $('.field-arrDays').attr('style','display:none')
});


$(function () {

    $(document).on("click",".date-wrap__link", function (e) {
        e.preventDefault();
        $("#datepickerSetTo").datepicker("show");
    });

    $(function(){
        $("#datepickerSetTo").datepicker({
            dateFormat: 'yy-mm-dd',

        });
    });

});

$(document).ready(function () {
    $(function () {

        $(document).on("click", ".date-wrap__link_two", function (e) {
            $("#datepickerTwo").datepicker("show");
        });

        var arrAllDays = JSON.parse($('#datepicker').attr('arralldays'));

        if (arrAllDays.length == 0) {
            $(function () {
                $("#datepickerTwo").datepicker({
                    beforeShowDay: function (date) {
                        var dayOfWeek = date.getDay();
                        var monday = $('#datepicker').attr('monday');
                        var tuesday = $('#datepicker').attr('tuesday');
                        var wednesday = $('#datepicker').attr('wednesday');
                        var thursday = $('#datepicker').attr('thursday');
                        var friday = $('#datepicker').attr('friday');
                        var saturday = $('#datepicker').attr('saturday');
                        var sunday = $('#datepicker').attr('sunday');

                        if (dayOfWeek == monday || dayOfWeek == tuesday || dayOfWeek == wednesday || dayOfWeek == thursday || dayOfWeek == friday || dayOfWeek == saturday || dayOfWeek == sunday) {
                            return [true];
                        } else {
                            return [false];
                        }
                    },
                    dateFormat: 'yy-mm-dd',
                    onSelect: function(date) {
                        $('#datepicker').val(date);
                        $('.fb-inline--entry').click();
                    },
                });
            });

        } else {
            $(function () {
                $("#datepickerTwo").datepicker({
                    dateFormat: 'yy-mm-dd',
                    beforeShowDay: disable,
                    onSelect: function(date) {
                        $('#datepicker').val(date);
                        $('.fb-inline--entry').click();
                        getDateExcursion();

                    },
                });
            });
        }

        function disable(d) {
            var dat = $.datepicker.formatDate("yy-mm-dd", d);
            for (var i = 0; i < arrAllDays.length; i++) {
                if ($.inArray(dat, arrAllDays) != -1) return [true];
                else return [false];
            }
        }

    });

});


$(document).on('click','#headDate',function () {
    $('#datepicker').val($(this).attr('date'));
    $('.fb-inline--entry').click();
    getDateExcursion();

});


function getDateExcursion() {

    $('#count-booking-form').empty();
    $('#booking-form').find('.jq-selectbox__dropdown ul').empty();

    var date = $('#datepicker').val();
    var idExcursion = $('#datepicker').attr('id-excursion');
    var valOneTimeExcursion = $('#headDate').attr('id-oneTime');
    $.ajax({
        url: '/count',
        type: 'post',
        data: {'date':date,'idExcursion':idExcursion,'valOneTimeExcursion':valOneTimeExcursion},
        success: function (data) {
            if (data > 0) {
                if(data > 10) {
                    var newData = 10;
                } else {
                    newData = data
                }

                $('#booking-form').find('.jq-selectbox__select-text').html('1');
                for (var i = 1; i <= newData; i++) {
                    if(i == 1) {
                        $('#booking-form').find('#count-booking-form').append('<option>' + i + '</option>');
                        $('#booking-form').find('.jq-selectbox__dropdown ul').append('<li id="excursionSelect" data-id="sel" class="selected sel">' + i + '</li>');
                    } else {
                        $('#booking-form').find('#count-booking-form').append('<option>' + i + '</option>');
                        $('#booking-form').find('.jq-selectbox__dropdown ul').append('<li id="excursionSelect">' + i + '</li>');
                    }
                }
            }
        }
    });
}

$(document).ready(function () {

    $(document).on('change','#datepicker',function () {
        getDateExcursion();
    });

    $(document).on('click', '#excursionSelect', function () {
        $('#excursionSelect[data-id=sel]').attr('class','');
        $('#booking-form').find('#count-booking-form-styler').attr('class','jq-selectbox jqselect fs');
        $('#booking-form').find('.jq-selectbox__dropdown').attr('style','position: absolute; left: 0px; top: auto; display: none; height: auto; bottom: auto;');
        $('#booking-form').find('.jq-selectbox__select-text').html($(this).html());
        $('#count-booking-form').val($(this).html());
        $(this).attr('class','selected sel');
        $(this).attr('data-id','sel');

    });

});

$(document).on('click', '#one_time_excursion', function () {
        checkValOneTimeExcursion();
});

function checkValOneTimeExcursion() {
    var url = window.location.href;

    if ($('#one_time_excursion').prop('checked')) {
        if (url.indexOf('en') != -1) {
            $('label[for=datepickerSetTo]').html('Tour Date');
        } else {
            $('label[for=datepickerSetTo]').html('Дата экскурсии:');
        }
        id = $('#one_time_excursion').attr('id');
        $('input[id="' + id + '"]').attr('value', 'Y');
        $('.days').attr('disabled', true);
        $('.days').attr('checked', false);
        $('.input-wrap.field-excursion-visitors').css({'display': 'inline-table'});
        $('.input-wrap.field-excursion-days_count').css({'display': 'none'});
    } else {
        if (url.indexOf('en') != -1) {
            $('label[for=datepickerSetTo]').html('Set to');
        } else {
            $('label[for=datepickerSetTo]').html('Установить до:');
        }
        id = $('#one_time_excursion').attr('id');
        $('input[id="' + id + '"]').attr('value', 'N');
        $('.days').attr('disabled', false);
        $('.input-wrap.field-excursion-visitors').css({'display': 'none'});
        $('.input-wrap.field-excursion-days_count').css({'display': ''});
    }
}

checkValOneTimeExcursion();

var countExcursionStr = $('.headDate').attr('id-count');
var url = window.location.href;

if(countExcursionStr == 0) {
    if (url.indexOf('en') != -1) {
        $('.fb-inline--entry').html('There are no seats');
    } else {
        $('.fb-inline--entry').html('Мест нет');
    }
    $('.fb-inline--entry').attr('href','#');
    $('.fb-inline--entry').attr('class','btn');
}



