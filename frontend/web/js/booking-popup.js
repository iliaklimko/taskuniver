$(function () {
    $(document)
        .ajaxStart(function () {
            $('.fancybox-overlay').css('z-index', 'auto');
            NProgress.start();
        })
        .ajaxStop(function () {
            NProgress.done();
        });
    $(document).on("click",".date-wrap__link", function (e) {
        e.preventDefault();
        $("#datepicker").datepicker("show");
    });

    var arrAllDays;

    try {
        arrAllDays = JSON.parse($('#datepicker').attr('arralldays'))
    } catch (e) {
        arrAllDays = [];
    }
    
    var countDate = arrAllDays.filter(Boolean).length;

    if (countDate == 1) {
        $('#datepicker').val($('#headDate').attr('date'));
    }
    $(function () {

        $("#datepicker").datepicker({
            dateFormat: 'yy-mm-dd',
            beforeShowDay: disable
        });
    });


    function disable  (d) {
        var dat = $.datepicker.formatDate("yy-mm-dd", d);
        for (var i=0; i < arrAllDays.length; i++){
            console.log(i)
            if ($.inArray(dat, arrAllDays)!=-1)  return [true];
            else return [false];
        }
    }


    $('#count-booking-form').change(function () {
        var count = parseInt($('#count-booking-form').val()),
            price = parseFloat($('#price-booking-form').val());
        var sum = count*price;
        sum = sum.toFixed(2);
        var sumString = '' + sum;
        $('#total-booking-form').html(sumString.replace('.', ','));

        if ($('#full_payment').length > 0) {
            var fullPayment = $('#full_payment').parent().find('span.payment-price');
            var percentPayment = $('#percent_payment').parent().find('span.payment-price:eq(0)');
            var percentPaymentGuide = $('#percent_payment').parent().find('p span.payment-price');
            var currencyInit = fullPayment.find('span');
            var currency = '<span class="'+currencyInit.attr('class')+'">'+currencyInit.html()+'</span>';
            var percent = parseInt($('#percent_payment').val());

            var percentSum = (sum * (percent / 100)).toFixed(2);
            var percentSumGuide = (sum - (sum * (percent / 100))).toFixed(2);

            fullPayment.html(sumString.replace('.', ',') + ' ').append(currency);
            percentPayment.html(percentSum.replace('.', ',') + ' ').append(currency);
            percentPaymentGuide.html(percentSumGuide.replace('.', ',') + ' ').append(currency);
        }
    });
    $('#booking-form').on('beforeSubmit', function (e) {
        $agree = $('#agreement');
        if ($agree.length) {
            if (!$agree.is(':checked')) {
                $('#agreement-error').removeClass('dnone');
            } else {
                $('#agreement-error').addClass('dnone');
                $.post($(this).attr('action'), $(this).serialize())
                 .done(function (data) {
                    var $paylerForm = $('#payler-form');
                    $paylerForm.attr('action', data.response.url);
                    $paylerForm.find('[name="session_id"]').val(data.response.session_id);
                    $paylerForm.find('[name="submit"]').click();
                 });
            }
        } else {
            $.post($(this).attr('action'), $(this).serialize())
                 .done(function (data) {
                    window.location = '/';
                 });
        }
        return false;
    });
});
