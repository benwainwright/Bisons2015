jQuery(document).ready(function () {


    if (false == Modernizr.inputtypes.date) {

        jQuery('input[type="date"]').datepicker({'dateFormat': 'yy-mm-dd'});

    }
});