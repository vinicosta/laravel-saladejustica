function autocomplete(element_name, element_id, url) {
    $(function() {
        showProgressBarOnDown(element_name);
        $("#" + element_name).autocomplete({
            source: url,
            select: function (event, ui) {
                $("#" + element_name).val(ui.item.label);
                $("#" + element_id).val(ui.item.id);
            }
        });
        hideProgressBarOnUp(element_name);
    });
}
