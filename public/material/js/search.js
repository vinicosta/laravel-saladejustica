function search(element_id, url, target) {
    $('#' + element_id).on('keyup', function () {
        $value = $(this).val();
        $.ajax({
            type: 'get',
            url: url,
            data: {
                'term': $value
            },
            success: function (data) {
                $(target).html(data);
            }
        });
        hideProgressBar();
    });

    $.ajaxSetup({
        headers: {
            'csrftoken': '{{ csrf_token() }}'
        }
    });
}
