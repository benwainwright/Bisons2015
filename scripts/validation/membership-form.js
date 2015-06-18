
function memShowErrors(map, list) {
    console.clear();
    var validator = this;
    var messages = validator.settings.messages;
    var invalidFields = validator.invalid;
    var successList = validator.successList;
    var form = jQuery(validator.currentForm);
    var sb = jQuery('#statusBar');
    var errorList;


    var templateErrorLi = jQuery('<li></li>' );

    console.log(validator);

    var numberOfInvalids = 0;

    for (key in invalidFields) {
        if (invalidFields.hasOwnProperty(key)) numberOfInvalids++;
    }

    console.log(numberOfInvalids);


    if (numberOfInvalids > 0) {

        var errorUL = jQuery('#statusBar .errorList');

        console.log(errorUL.length);
        if ( errorUL.length == 0 ) {
            errorUL = jQuery("<ul class='errorList'></ul>").appendTo(sb);
            sb.show();
        }

        errorUL.empty();

        // Add class to all failed elements
        for (var key in invalidFields) {

            if (invalidFields.hasOwnProperty(key)) {
                var invalid = form.find('[name="' + key + '"]');
                invalid.addClass('error');
                templateErrorLi.clone().text(messages[key]).appendTo(errorUL);
            }
        }

    }

    else {
        sb.hide();
        errorList.detach();
    }

    // remove from all passed elements
    successList.forEach(function(e) {
       jQuery(e).removeClass('error');
    });
}

function memOnFocusOut(element) {
    jQuery(element).valid();
}

jQuery(document).ready(function () {

    jQuery('#membershipform_payment').validate({
        onfocusout: memOnFocusOut,
        groups: {
            DateofBirth: "dob-day dob-month dob-year"
        },
        showErrors: memShowErrors,
        messages: {
            firstname: "The 'first name' field cannot be blank",
            surname: "The 'surname' field cannot be blank"
        }

    });

    jQuery.validator.addClassRules("min2chars", {minlength: 2});
    jQuery.validator.addClassRules("postcode", {postalCode: true});


    jQuery("input[type='tel']").each(function () {
        jQuery(this).rules("add", {phoneUK: true});
    });
});
