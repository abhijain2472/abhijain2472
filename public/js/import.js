$(document).ready(function() {

    $("button.csv-download").click(function() {
        var button = $(this);
        $.ajax({
            url: button.attr('ajax-data'),
            type: 'POST',
            data: { '_token': $("#csrfToken").val() },
            beforeSend: function() {
                button.attr("disabled='disabled'");
            },
            success: function(data) {
                if (data != "" || data != null) {
                    window.open(data);
                }
            },
            complete: function() {
                button.removeAttr("disabled");
            }
        });
    });

    $(".csv-upload").click(function() {
        $(this).siblings('form').children('input[type=file]').click();
    });

    $("#file_upload").change(function() {
        var file = $(this);
        if (file.val() != "") {
            $("#upload_form").submit();
        }
    });

    $("#upload_form").submit(function() {
        var button = $(".csv-upload");
        var formData = $(this).serialize();
        $.ajax({
            url: "/upload-csv",
            type: 'POST',
            data: new FormData(this),
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
                button.attr("disabled", '');
            },
            success: function(data) {
                $("#upload_form").children("input[type=reset]").click();
                data = JSON.parse(data);
                if (data['success'] == "success") {
                    $("#summary-div").html(data['data']['html']);
                    $("#rowsuccess").html(data['data']['rowsuccess'] + "<div>Correct</div>");
                    $("#rowskipped").html(data['data']['rowskipped'] + "<div>Incorrect</div>");
                    $(".summary").show();
                    if (data['data']['rowsuccess'] == 0) {
                        $(".add-btn.summary").hide();
                    }
                }
            },
            complete: function() {
                button.removeAttr("disabled");
            }
        });
        return false;
    });

    $(".ajax-save").click(function() {
        var button = $(this);
        $.ajax({
            url: "/upload-csv-action",
            type: 'POST',
            data: { '_token': $("#csrfToken").val() },
            beforeSend: function() {
                button.attr("disabled", '');
            },
            success: function(data) {
                if (data == "done") {
                    window.location.href = '/blog-post-list';
                }
            },
            complete: function() {
                button.removeAttr("disabled");
            }
        });
    });
});