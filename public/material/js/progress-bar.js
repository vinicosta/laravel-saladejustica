function showProgressBar() {
    $('.progress').show();
}

function showProgressBarOnDown(element_id) {
    $('#' + element_id).on('keydown', function () {
        showProgressBar();
    });
}

function hideProgressBar() {
    $('.progress').hide();
}

function hideProgressBarOnUp(element_id) {
    $('#' + element_id).on('keyup', function () {
        hideProgressBar();
    });
}
