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

setTimeout(() => {
    $(".alert .close").click();
}, 6000);
var index = 0;

function successMessage(message) {
    $(".card-body").prepend("<div class='alert alert-success alert-dismissible ajax' id='" + (index++) + "'><button type='button' class='close' data-dismiss='alert'>&times;</button><strong>Success!</strong> " + message + "</div>");
    return index;
}

function failMessage(message) {
    $(".card-body").prepend("<div class='alert alert-danger alert-dismissible ajax' id='" + (index++) + "'><button type='button' class='close' data-dismiss='alert'>&times;</button><strong>Error!</strong> " + message + "</div>");
    return index;
}
