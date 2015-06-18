jQuery(document).ready(function () {
    jQuery('form').validate({
        onfocusout: function (element) {
            jQuery(element).valid();
        },

        groups: {
            DateofBirth: "dob-day dob-month dob-year"
        },

        errorLabelContainer: '#statusBar',
        wrapper: 'ul',
        errorElement: 'errorElement'

    });

    jQuery.validator.addClassRules("min2chars", {minlength: 2});
    jQuery.validator.addClassRules("postcode", {postalCode: true});


    jQuery("input[type='tel']").each(function () {
        jQuery(this).rules("add", {phoneUK: true});
    });
});
