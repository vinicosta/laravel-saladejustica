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

function autocompleteMultiple(element_name, element_id, url) {
    $(function () {
        function split(val) {
            return val.split(/,\s*/);
        }
        function extractLast(term) {
            return split(term).pop();
        }

        $("#" + element_name)
            // don't navigate away from the field on tab when selecting an item
            .on("keydown", function (event) {
                if (event.keyCode === $.ui.keyCode.TAB &&
                    $(this).autocomplete("instance").menu.active) {
                    event.preventDefault();
                }
            })
            .autocomplete({
                source: function (request, response) {
                    $.getJSON(url, {
                        term: extractLast(request.term)
                    }, response);
                },
                search: function () {
                    // custom minLength
                    var term = extractLast(this.value);
                    if (term.length < 2) {
                        return false;
                    }
                },
                focus: function () {
                    // prevent value inserted on focus
                    return false;
                },
                select: function (event, ui) {
                    var terms = split(this.value);
                    var ids = split($("#" + element_id).val());
                    
                    // remove the current input
                    terms.pop();
                    ids.pop();
                    
                    // add the selected item
                    terms.push(ui.item.value);
                    ids.push(ui.item.id);
                    
                    // add placeholder to get the comma-and-space at the end
                    terms.push("");
                    ids.push("");
                    this.value = terms.join(", ");
                    $("#" + element_id).val(ids.join(","));
                    
                    return false;
                }
            });
    });
}
