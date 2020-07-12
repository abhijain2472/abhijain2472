$(document).ready(function() {
    $(".summary").hide();
    $(".custom-file-input").on("change", function() {
        if ($(this).val() == "") {
            $(this).siblings(".custom-file-label").addClass("selected").html("Choose file");
        } else {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        }
    });

    $("button.navigate").click(function() {
        if ($(this).attr("data-src")) {
            window.location.href = $(this).attr("data-src");
        }
    });

    $("select.ajax").each(function() {
        var select = $(this);
        $.ajax({
            url: $(this).attr("ajax-url"),
            type: "GET",
            data: $(this).attr("ajax-data"),
            success: function(data) {
                $(select).append(data);
            }
        });
    });
});