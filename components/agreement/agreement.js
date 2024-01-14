
$(document).ready(function () {
    // Полифилл для тега dialog
    var dialog = document.querySelector('.js-agreement-modal');
    if(dialog == null){
        return false;
    }
    dialogPolyfill.registerDialog(dialog);

    var btnOpenSelector = '.js-open-agreement';
    var btnCloseSelector = '.js-close-agreement';

        // открытие модалки с соглашением на обработку пользовательских данных
        $(document.body).on('click', btnOpenSelector, function (e) {
            e.preventDefault();

            if (dialog.length < 1) {
                $.ajax({
                    type: "GET",
                    url: mgBaseDir + "/ajaxrequest",
                    data: {
                        layoutAgreement: 'agreement'
                    },
                    dataType: "HTML",
                    success: function (response) {
                        $('body').append(response);
                    }
                });
            } else {
                // modalOverlay.show();
                dialog.showModal();
            }
        });

    // закрытие модалки с соглашением на обработку пользовательских данных
    $(document.body).on('click', btnCloseSelector, function (e) {
        e.preventDefault();

        // modalOverlay.hide();
        dialog.close();
    });
});

