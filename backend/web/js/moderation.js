$(document).ready(function () {
    // var PROMPT_INPUT = 'div.modal-body > div > div > input';
    // setTimeout(function () {
    //     if ($(PROMPT_INPUT).val() && $(PROMPT_INPUT).val().trim()) {
    //         $('#btn-ok').attr('disabled', '');
    //     } else {
    //         $('#btn-ok').attr('disabled', true);
    //     }
    // }, 500);
    // $(document).on('keyup', PROMPT_INPUT, function () {
    //     console.log($(this).val());
    // });
    // $('.btn-reject').on('click', function(e) {
    //     e.preventDefault();
    //     var rejectionUrl = $(this).attr('href');
    //     krajeeDialog.prompt({label: 'Provide a reason'}, function (rejectionReason) {
    //         if (rejectionReason) {
    //             rejectionReason = rejectionReason.trim();
    //             if (rejectionReason) {
    //                 $.post(rejectionUrl, {reason: rejectionReason});
    //             } else {
    //                 alert('Provide a reason!');
    //             }
    //         } else {
    //             console.log('rejection canceled');
    //         }
    //     });
    // });

    $('.btn-reject').on('click', function(e) {
        e.preventDefault();
        var rejectionUrl = $(this).attr('href');
        swal(
            {
                title: "Reject",
                text: "Provide a reason:",
                type: "input",
                showCancelButton: true,
                closeOnConfirm: false,
                animation: "slide-from-top",
                inputPlaceholder: "Write something"
            },
            function(inputValue) {
                if (inputValue === false) return false;
                inputValue = inputValue.trim();
                if (inputValue === "") {
                    swal.showInputError("You need to write something!");
                    return false;
                }
                $.post(rejectionUrl, {reason: inputValue});
            }
        );
        // krajeeDialog.prompt({label: 'Provide a reason'}, function (rejectionReason) {
        //     if (rejectionReason) {
        //         rejectionReason = rejectionReason.trim();
        //         if (rejectionReason) {
        //             $.post(rejectionUrl, {reason: rejectionReason});
        //         } else {
        //             alert('Provide a reason!');
        //         }
        //     } else {
        //         console.log('rejection canceled');
        //     }
        // });
    });
});
