$(document).ready(function() {
    $.ajax({
        url: '',
        type: "GET",
        data: {},
        success: function(data) {
            var count = 0;
            $("#filterInfo select, #filterInfo input[type!=button]").each(function() {
                var tagName = $(this).prop("tagName").toLowerCase();
                switch (tagName) {
                    case "input":
                        if ($.cookie("search_" + $(this).attr("id")) != "") {
                            $(this).val($.cookie("search_" + $(this).attr("id")));
                            count++;
                        }
                        break;
                    case "select":
                        if ($.cookie("search_" + $(this).attr("id")) != "null") {
                            $(this).val($.cookie("search_" + $(this).attr("id")));
                            count++;
                        }
                        break;

                    default:
                        break;
                }
            });
            if (count > 0) {
                $("#filterInfo input[type=button]#search-btn").trigger("click");
            }
        }
    });

    var action = [];
    $("#filterInfo input[type=button]#search-btn").click(function() {
        var action = [];
        var empty = true;
        $("#filterInfo input[type!=button], #filterInfo select").each(function() {
            if ($(this).val() == null || $(this).val() == "") {} else {
                empty = false;
                action.push([$(this).attr('id'), $(this).val()]);
            }
        });
        $("#dataTable").DataTable().destroy();
        fillTable(action);
        $("#filterInfo select, #filterInfo input[type!=button]").each(function() {
            $.cookie("search_" + $(this).attr("id"), $(this).val());
        });
    });

    fillTable(action);

    $("#filterInfo button[type=reset]").click(function() {
        $("#filterInfo input[type!=button], #filterInfo select").val("");
        $("#filterInfo input[type=button]#search-btn").click();
    });
});

function fillTable(action) {
    var orderFalseIndex = [];
    $("thead th").each(function(index) {
        if ($(this).attr("data-sort") != undefined && $(this).attr("data-sort") == 'false') {
            orderFalseIndex.push(index);
        }
    });
    var data = "";
    if (action.length > 0) {
        action.forEach(function(item, index) {
            if (index > 0) {
                data += "&";
            }
            data += item[0] + "=" + item[1];
        });
    }
    var form = $("#dataTable").attr("data-load");

    var table = $('#dataTable.ajax').DataTable({
        "columnDefs": [
            { "orderable": false, "targets": orderFalseIndex }
        ],
        "processing": true,
        "serverSide": true,
        "searching": false,
        "stateSave": true,
        "ajax": {
            "url": form,
            "type": "POST",
            "data": { data: data, _token: $("#csrf").val() }
        }
    });

    var table = $('#dataTable:not(.ajax)').DataTable({
        "columnDefs": [
            { "orderable": false, "targets": orderFalseIndex }
        ],
    });
}