$(function() {
    $(".clear").click(function() {
        var id = $(this).attr("data-clear");
        $(id).val("");
    });
    $("form").submit(function() {
        var validation_data = [];
        var validation_data_optional = [];
        var validation_data_optional_msg = [];
        var validation_data_optional_group = [];
        $("div.form-group.validate").each(function() {
            if ($(this).attr("data-validation") != "" && $(this).attr("data-validation") != undefined) {
                if ($(this).attr("data-validation") == "optional") {
                    validation_data_optional_msg.push([$(this).attr("option-group"), $(this).attr("validation-msg")]);
                    validation_data_optional.push([$(this).attr("option-group"), $(this).find("input,select,textarea").attr("id")]);
                    if (!validation_data_optional_group.includes($(this).attr("option-group"))) {
                        validation_data_optional_group.push($(this).attr("option-group"));
                    }
                } else {
                    validation_data.push([$(this).find("input,select,textarea").attr("id"), $(this).attr("data-validation"), $(this).attr("validation-msg").split("|"), $(this).attr("data-element"), $(this).attr("data-value")]);
                }
            }

        });
        console.log(validation_data);

        console.log(validation_data_optional);
        console.log(validation_data_optional_msg);
        console.log(validation_data_optional_group);
        var validate = true;

        for (var i = 0; i < validation_data.length; i++) {
            var input = $("#" + validation_data[i][0]);
            $(input).parent().find("p.text-danger").remove();
            if (validation_data[i][1] === "required") {
                if (input.val() == "") {
                    $(input).parent().append("<p class='text-danger'>" + validation_data[i][2] + "</p>");
                    validate = false;
                } else {
                    if (input.attr("data-min-length") != "" && input.attr("data-max-length") != "") {
                        if (input.val().length < Number(input.attr("data-min-length")) || input.val().length > Number(input.attr("data-max-length"))) {
                            if (Number(input.attr("data-min-length")) != Number(input.attr("data-max-length"))) {
                                $(input).parent().append("<p class='text-danger'>Input must between " + input.attr('data-min-length') + "-" + input.attr('data-max-length') + " characters.</p>");
                            } else {
                                $(input).parent().append("<p class='text-danger'>" + input.attr('data-range-message') + "</p>");
                            }
                            validate = false;
                        }
                    }
                }
            }

            if (validation_data[i][1] === "required_numeric") {
                if (input.val() == "") {
                    $(input).parent().append("<p class='text-danger'>" + validation_data[i][2][0] + "</p>");
                    validate = false;
                } else if (isNaN($(input).val())) {
                    $(input).parent().append("<p class='text-danger'>" + validation_data[i][2][1] + "</p>");
                    validate = false;
                } else {
                    if (input.attr("data-min-length") != "" && input.attr("data-max-length") != "") {
                        if (input.val().length < Number(input.attr("data-min-length")) || input.val().length > Number(input.attr("data-max-length"))) {
                            if (Number(input.attr("data-min-length")) != Number(input.attr("data-max-length"))) {
                                $(input).parent().append("<p class='text-danger'>Input must between " + input.attr('data-min-length') + "-" + input.attr('data-max-length') + " characters.</p>");
                            } else {
                                $(input).parent().append("<p class='text-danger'>" + input.attr('data-range-message') + "</p>");
                            }
                            validate = false;
                        }
                    }
                }
            }

            if (validation_data[i][1] === "required_email") {
                var regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,3}$/;
                if (input.val() == "") {
                    $(input).parent().append("<p class='text-danger'>" + validation_data[i][2][0] + "</p>");
                    validate = false;
                } else if (!regex.test(input.val())) {
                    $(input).parent().append("<p class='text-danger'>" + validation_data[i][2][1] + "</p>");
                    validate = false;
                }
            }

            if (validation_data[i][1] === "email") {
                var regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,3}$/;
                if (input.val() != "" && !regex.test(input.val())) {
                    $(input).parent().append("<p class='text-danger'>" + validation_data[i][2] + "</p>");
                    validate = false;
                }
            }

            if (validation_data[i][1] === "required_contact") {
                var regex = /^(?=.*[0-9])(?=.*[A-z])(?=.*[*@!#$%\-]).{8,15}$/;
                if (input.val() == "") {
                    $(input).parent().append("<p class='text-danger'>" + validation_data[i][2][0] + "</p>");
                    validate = false;
                } else if (!regex.test(input.val())) {
                    $(input).parent().append("<p class='text-danger'>" + validation_data[i][2][1] + "</p>");
                    validate = false;
                } else {
                    if (input.attr("data-min-length") != "" && input.attr("data-max-length") != "") {
                        if (input.val().length < Number(input.attr("data-min-length")) || input.val().length > Number(input.attr("data-max-length"))) {
                            if (Number(input.attr("data-min-length")) != Number(input.attr("data-max-length"))) {
                                $(input).parent().append("<p class='text-danger'>Input must between " + input.attr('data-min-length') + "-" + input.attr('data-max-length') + " characters.</p>");
                            } else {
                                $(input).parent().append("<p class='text-danger'>" + input.attr('data-range-message') + "</p>");
                            }
                            validate = false;
                        }
                    }
                }
            }

            if (validation_data[i][1] === "contact") {
                var regex = /^[0-9]{10}$/;
                if (input.val() != "" && !regex.test(input.val())) {
                    $(input).parent().append("<p class='text-danger'>" + validation_data[i][2] + "</p>");
                    validate = false;
                } else {
                    if (input.attr("data-min-length") != "" && input.attr("data-max-length") != "") {
                        if (input.val().length < Number(input.attr("data-min-length")) || input.val().length > Number(input.attr("data-max-length"))) {
                            if (Number(input.attr("data-min-length")) != Number(input.attr("data-max-length"))) {
                                $(input).parent().append("<p class='text-danger'>Input must between " + input.attr('data-min-length') + "-" + input.attr('data-max-length') + " characters.</p>");
                            } else {
                                $(input).parent().append("<p class='text-danger'>" + input.attr('data-range-message') + "</p>");
                            }
                            validate = false;
                        }
                    }
                }
            }

            if (validation_data[i][1] === "required_password") {
                var regex = /^[0-9]{10}$/;
                if (input.val() == "") {
                    $(input).parent().append("<p class='text-danger'>" + validation_data[i][2][0] + "</p>");
                    validate = false;
                } else if (!regex.test(input.val())) {
                    $(input).parent().append("<p class='text-danger'>" + validation_data[i][2][1] + "</p>");
                    validate = false;
                } else {
                    if (input.attr("data-min-length") != "" && input.attr("data-max-length") != "") {
                        if (input.val().length < Number(input.attr("data-min-length")) || input.val().length > Number(input.attr("data-max-length"))) {
                            if (Number(input.attr("data-min-length")) != Number(input.attr("data-max-length"))) {
                                $(input).parent().append("<p class='text-danger'>Input must between " + input.attr('data-min-length') + "-" + input.attr('data-max-length') + " characters.</p>");
                            } else {
                                $(input).parent().append("<p class='text-danger'>" + input.attr('data-range-message') + "</p>");
                            }
                            validate = false;
                        }
                    }
                }
            }

            if (validation_data[i][1] === "conditional_required") {
                var element = validation_data[i][3];
                var inputType = $(element).attr("type");
                if (inputType == "radio" || inputType == "checkbox") {
                    if (validation_data[i][4] == $(element + ":checked").val()) {
                        if (input.val() == "") {
                            $(input).parent().append("<p class='text-danger'>" + validation_data[i][2][0] + "</p>");
                            validate = false;
                        }
                    }
                } else {
                    if (validation_data[i][4] == $(element).val()) {
                        if (input.val() == "") {
                            $(input).parent().append("<p class='text-danger'>" + validation_data[i][2][0] + "</p>");
                            validate = false;
                        }
                    }
                }
            }
        }

        for (var i = 0; i < validation_data_optional_group.length; i++) {
            var elem_values = [];
            if (validation_data_optional[i][0] == validation_data_optional_group[i]) {
                for (var j = 0; j < validation_data_optional.length; j++) {
                    elem_values.push([validation_data_optional[j][1], validation_data_optional_msg[j][1]]);
                    $("#group_error_" + validation_data_optional_group[i]).text("");
                }
            }
            var invalid = true;
            for (var j = 0; j < elem_values.length; j++) {
                $("#" + elem_values[j][0]).parent().find("p.text-danger").remove();
                if ($("#" + elem_values[j][0]).val() != "") {
                    invalid = false;
                }
            }

            if (invalid == true) {
                for (var j = 0; j < elem_values.length; j++) {
                    $("#" + elem_values[j][0]).parent().append("<p class='text-danger'>" + elem_values[j][1] + "</p>");
                }
                validate = false;
            }
            console.log(elem_values);
        }

        if (!validate) {
            return false;
        }
    });
});