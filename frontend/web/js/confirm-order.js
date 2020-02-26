$(function () {
    var $orderIdConfirmationInput = $('#order-id-confirmation-form');
    var $orderIdRenouncementInput = $('#order-id-renouncement-form');
    $('.confirmation').click(function () {
        var val = $(this).data('order-id');
        $orderIdConfirmationInput.val(val);
    });
    $('.renouncement').click(function () {
        var val = $(this).data('order-id');
        $orderIdRenouncementInput.val(val);
    });
});
