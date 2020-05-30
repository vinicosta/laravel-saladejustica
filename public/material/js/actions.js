function reading(title_id) {
    showProgressBar();
    $.ajax({
        type: 'post',
        url: url_reading,
        data: {
            'title_id': title_id,
            'user_id': user_id,
            '_token': csrftoken
        },
        success: function (data) {
            $('#btn-reading-' + title_id).hide();
            $('#btn-unreading-' + title_id).show();
            hideProgressBar();
        }
    });
}

function unreading(title_id) {
    showProgressBar();
    $.ajax({
        type: 'delete',
        url: url_reading,
        data: {
            'title_id': title_id,
            'user_id': user_id,
            '_token': csrftoken
        },
        success: function (data) {
            $('#btn-unreading-' + title_id).hide();
            $('#btn-reading-' + title_id).show();
            hideProgressBar();
        }
    });
}

function collection(issue_id) {
    showProgressBar();
    $.ajax({
        type: 'post',
        url: url_collection,
        data: {
            'issue_id': issue_id,
            'user_id': user_id,
            'added_date': '',
            '_token': csrftoken
        },
        success: function (data) {
            $('#btn-collection-' + issue_id).hide();
            $('#btn-uncollection-' + issue_id).show();
            hideProgressBar();
        }
    });
}

function uncollection(issue_id) {
    showProgressBar();
    $.ajax({
        type: 'delete',
        url: url_collection,
        data: {
            'issue_id': issue_id,
            'user_id': user_id,
            '_token': csrftoken
        },
        success: function (data) {
            $('#btn-uncollection-' + issue_id).hide();
            $('#btn-collection-' + issue_id).show();
            hideProgressBar();
        }
    });
}

function readed(issue_id, title_id) {
    showProgressBar();
    $.ajax({
        type: 'post',
        url: url_readed,
        data: {
            'issue_id': issue_id,
            'title_id': title_id,
            'user_id': user_id,
            'readed_date': '',
            '_token': csrftoken
        },
        success: function (data) {
            $('#btn-readed-' + issue_id).hide();
            $('#btn-unreaded-' + issue_id).show();
            hideProgressBar();
        }
    });
}

function unreaded(issue_id) {
    showProgressBar();
    $.ajax({
        type: 'delete',
        url: url_readed,
        data: {
            'issue_id': issue_id,
            'user_id': user_id,
            '_token': csrftoken
        },
        success: function (data) {
            $('#btn-unreaded-' + issue_id).hide();
            $('#btn-readed-' + issue_id).show();
            hideProgressBar();
        }
    });
}
