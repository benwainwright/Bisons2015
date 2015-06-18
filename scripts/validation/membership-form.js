jQuery(document).ready(function () {
    jQuery('#membershipform_payment').validate({
        onfocusout: function (element) {
            jQuery(element).valid();
        },

        groups: {
            DateofBirth: "dob-day dob-month dob-year"
        },

        errorContainer: '#statusBar',
        errorLabelContainer: '#statusBar ul.errors',
        wrapper: 'li',
        errorElement: 'label',

        messages: {
            firstname:"The 'first name' field cannot be blank",
            surname: "The 'surname' field cannot be blank"
        }

    });

    jQuery.validator.addClassRules("min2chars", {minlength: 2});
    jQuery.validator.addClassRules("postcode", {postalCode: true});


    jQuery("input[type='tel']").each(function () {
        jQuery(this).rules("add", {phoneUK: true});
    });
});
