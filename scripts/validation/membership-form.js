
function memShowErrors(map, list) {
    var validator = this;
    var messages = validator.settings.messages;
    var invalidFields = validator.invalid;
    var successList = validator.successList;
    var form = jQuery(validator.currentForm);
    var sb = jQuery('#statusBar');
    var errorList;
    var formInfoOn = jQuery('#statusBar.statusBarInfo').length > 0;

    var templateErrorLi = jQuery('<li><label class=\'error\'></label></li>' );

    var numberOfInvalids = 0;
    var errorUL = jQuery('#statusBar .errorList');
    var errorNote = jQuery('<p class="errorSubheading">Click the error to be taken there...</p>');

    for (key in invalidFields) {
        if (invalidFields.hasOwnProperty(key)) numberOfInvalids++;
    }


    if (numberOfInvalids > 0 ) {


        if ( errorUL.length == 0 ) {
            errorUL = jQuery("<ul class='errorList'></ul>")

            if(!formInfoOn) {
                errorNote.appendTo(sb);
                errorUL.appendTo(sb);
                sb.show();

            }
        }

        errorUL.empty();

        // Add class to all failed elements
        for (var key in invalidFields) {

            if (invalidFields.hasOwnProperty(key)) {
                var invalid = form.find('[name="' + key + '"]');
                invalid.addClass('error');
                var newLI = templateErrorLi.clone();
                newLI.find('label').text(messages[key]).attr('for', key);
                newLI.appendTo(errorUL);

            }
        }

    }

    else {
        sb.hide();
        errorUL.detach();
        errorNote.detach();
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

    var notBlankString = " cannot be left blank";

    var messages = {
        joiningas: "Are you joining as a player or a supporter?",
        firstname: "Your first name" + notBlankString,
        surname: "Your surname" + notBlankString,
        gender: "Your gender" + notBlankString,
        othergender: "You have stipulated your gender as 'other'; please give details",
        "dob-day": "You missed out the day in your date of birth",
        "dob-month": "You missed out the month in your date of birth",
        "dob-year": "You missed out the year in your date of birth",
        email_addy: "Please ensure you enter a valid email address",
        contact_number: "Please enter a valid UK phone number",
        streetaddyl1: "The first line of your address" + notBlankString,
        postcode: "Please enter a valid UK postcode",
        medconsdisabyesno: "Please select whether you have any current medical conditions or disabilities",
        allergiesyesno: "Please select whether you have any allergies",
        injuredyesno: "Please select whether you have ever been injured",
        nokfirstname: "The first name of your next of kin" + notBlankString,
        noksurname: "The surname of your next of kin" + notBlankString,
        nokrelationship: "The relationship that your next of kin has to you" + notBlankString,
        nokcontactnumber: "Please enter a valid UK phone number for your next of kin",
        sameaddress: "Please indicate whether or not you live at the same address as your next of kin",
        nokstreetaddy: "You have indicated that you live at a different address to your next of kin. Please give that address",
        nokpostcode: "Please enter a valid UK postcode for your next of kin",
        hoursaweektrain: "Please indicate how many hours a week you train",
        othersports: "Please indicate whether you are involved in any other sports or physical activities; 'none' is an acceptable answer.",
        playedbefore: "Please indicate whether you have played rugby before",
        whereandseasons: "You have indicate that you have played rugby before. Please let us know where you played and for how many seasons",
        height: "Your height" + notBlankString,
        weight: "Your weight" + notBlankString,
        howmanycigsperday: "Since you have indicated that you are a smoker, please let us know often you smoke",
        howdidyouhear: "Please let us know how you heard about the Bisons",
        topsize: "Please indicate what size you would like your exclusive Bisons social top to be",
        payWhen: "Please indicate when you will be paying ",
        DayOfMonth: "Please choose the specific day of the month you would like your payment to be debited on",
        weekDay: "Please choose which weekday you would like your payment to be debited on",
        whichWeekDay: "You have indicated that you will be paying by Direct Debit. Please let us know when you would like your payment to be debited",
        playermembershiptypemonthly: "Please choose what type of membership you would like"

    };





    jQuery('#membershipform_payment').validate({
        onfocusout: memOnFocusOut,
        groups: {
            DateofBirth: "dob-day dob-month dob-year"
        },
        showErrors: memShowErrors,
        messages: messages

    });

    jQuery.validator.addClassRules("postcode", {postalCode: true});


    jQuery("input[type='tel']").each(function () {
        jQuery(this).rules("add", {phoneUK: true});
    });
});
