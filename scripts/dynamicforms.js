function addRowToTableWhenFull() {
    var lastRow = jQuery(this).parent().parent().parent().find('tr:last');
        var clonedRow = jQuery(lastRow).clone();
        var full = true;

        clonedRow.find('input').each(function(element){

            if(jQuery(this).val() == '') {
                full = false;
            }

            else {
                jQuery(this).val('');
                jQuery(this).removeClass();
            }

        });

        if(full) {

            lastRow.keydown(null);


            clonedRow.insertAfter(lastRow);
            var clonedInputs = clonedRow.find('input');

            jQuery.each(clonedInputs, function() {

                rowNum = Number(jQuery(this).attr('name').slice(-1));
                rowNum++;
                withoutRowNum = jQuery(this).attr('name').substring(0, jQuery(this).attr('name').length - 1);
                newName = withoutRowNum + String(rowNum);
                jQuery(this).attr('name', newName);

            });

            clonedInputs.keydown(addRowToTableWhenFull);
            clonedInputs.focus(setInputAsFocused);
            clonedInputs.focusout(setInputAsUnfocused);

        }
}

var setInputAsFocused = function () {

    var text = jQuery(this).siblings('.forminfo').html();
    var sb = jQuery('#statusBar');
    
    // If there is text in a forminfo, put it into the status bar
    if(text) {
        display = sb.css('display');

        sb.data('prevDisplay', display);
        sb.data('prevHTML', sb.html());
        sb.addClass('statusBarInfo').html(text).show();
    }

    // Get dimensions of focused element
    var windowHeight = jQuery(window).height();
    var sbHeight = sb.outerHeight();
    var elementHeight = jQuery(this).outerHeight();
    var sbTopPosition = windowHeight - sbHeight;
    var elementTopRelativeToDocument = jQuery(this).offset().top;
    var elementBottomRelativeToWindow =  jQuery(this).offset().top - jQuery(window).scrollTop();

    var statusBarIsVisible = sb.css('display') == 'block';

    // If the element is hidden behind the statusbar, scroll up a bit
    if (statusBarIsVisible && elementBottomRelativeToWindow > sbTopPosition) {

        var scroll = elementTopRelativeToDocument - windowHeight + sbHeight + elementHeight + 10;

        $('html, body').animate({
            scrollTop: scroll
        }, 500);
    }


    jQuery(this).addClass('focusedinput');
    jQuery(this).siblings('label:not(.error)').addClass('focusedinput');
    jQuery(this).parents('.inlinediv').siblings('label').addClass('focusedinput');
}

function setInputAsUnfocused() {

        var sb = jQuery('#statusBar');

        if(sb.hasClass('statusBarInfo')) {
            sb.removeClass('statusBarInfo');
        }
        jQuery(this).parent().parent().find('.fieldsetInfo');

        html = sb.data('prevHTML');

        if(html) {
            sb.html(html);
        }

        if (sb.find('li').length = 0 ) {
            sb.hide();
        }

        jQuery(this).removeClass('focusedinput');
        jQuery(this).siblings('label').removeClass('focusedinput');
        jQuery(this).parents('.inlinediv').siblings('label').removeClass('focusedinput');
}


jQuery(document).ready(function () {

    jQuery('#committeeSelectPlayer').change(function () {

        var pathArray = window.location.pathname.split('?');
        var baseUrl = pathArray[0];
        if (jQuery(this).val() == 'me') {
            window.location = baseUrl;
        }
        else {
            window.location = baseUrl + '?player_id=' + jQuery(this).val();
        }
    });

    var match_event_box_count = 0;


    var adminNotBlankAddNew = function () {


        if (jQuery(this).val() != '') {
            var newField = jQuery(this).parent().parent().clone();
            newField.appendTo(jQuery(this).parent().parent().parent());
            var inputs = newField.find('select').each(function () {
                var split = jQuery(this).attr('name').split('_');
                split[split.length - 1]++;
                jQuery(this).attr('name', split.join('_'));

            });
            jQuery(this).off('change');
            jQuery('.adminNotBlankaddNew').last().change(adminNotBlankAddNew);

        }
    };


    jQuery('.adminNotBlankaddNew').last().change(adminNotBlankAddNew);


    jQuery('#payWhen').change(function () {

        switch (jQuery(this).val()) {


            case 'specificDay':
                jQuery('#payDateDiv').show();
                jQuery('#payWeekDayDiv').hide();
                break;


            case 'specificWeekday':
                jQuery('#payDateDiv').hide();
                jQuery('#payWeekDayDiv').show();
                break;

            default:
                jQuery('#payDateDiv').hide();
                jQuery('#payWeekDayDiv').hide();
        }

    });

    jQuery('#allDay').click(function () {

        if (jQuery(this).prop('checked')) {
            jQuery('#time').parent().parent().hide(400);
            jQuery('#time').val('');
            validator.remove_error(jQuery('#time'));
            jQuery('#endtime').parent().parent().hide(400);
            jQuery('#endtime').val('');
            validator.remove_error(jQuery('#endtime'));
        }
        else {
            jQuery('#time').parent().parent().show("blind");
            jQuery('#endtime').parent().parent().show("blind");
        }
    });


    jQuery('input, select, textarea').focus(setInputAsFocused);
    jQuery('input, select, textarea').focusout(setInputAsUnfocused);


    jQuery('table.autoAddRow').find('tbody tr:last input').keydown(addRowToTableWhenFull);

    jQuery('#joiningas').change(function () {
        if (jQuery(this).val() == 'Supporter') {
            jQuery('.playersonly').hide();
            jQuery('.supportersonly').show();
            jQuery('#paymentFieldset').show();
            jQuery('#playermempaymonthly').find('select').val('');
            jQuery('#playermempaysingle').find('select').val('');
        }
        else if (jQuery(this).val() == 'Player') {
            jQuery('.playersonly').show();
            jQuery('.supportersonly').hide();
            jQuery('#supportermempaymonthly').find('select').val('');
            jQuery('#supportermempaysingle').find('select').val('');
            jQuery('#paymentFieldset').show();
        }
        else if (jQuery(this).val() == 'Player') {
            jQuery('#paymentFieldset').hide();

        }
    });

    jQuery('#already').change(function () {
        if (jQuery(this).val() == 'Yes') {
            jQuery('#inputcarddetails').hide();
        }
        else {
            jQuery('#inputcarddetails').show();
        }
    });

    jQuery('#medconsdisabyesno').change(function () {
        if (jQuery(this).val() == 'Yes') {
            jQuery('#conddisablefieldset').show();
        }
        else {
            jQuery('#conddisablefieldset').hide();
        }
    });

    jQuery('#sameaddress').change(function () {
        if (jQuery(this).val() == 'No') {
            jQuery('#nokaddygroup').show();
        }
        else {
            jQuery('#nokaddygroup').hide();
        }
    });

    jQuery('#allergiesyesno').change(function () {
        if (jQuery(this).val() == 'Yes') {
            jQuery('#allergiesfieldset').show();
        }
        else {
            jQuery('#allergiesfieldset').hide();
        }
    });

    jQuery('#gender').change(function () {
        if (jQuery(this).val() == 'Other') {
            jQuery('#othergender').css('display', 'table')
        }
        else {
            jQuery('#othergender').hide('display', 'none');
        }
    });

    jQuery('#smoking').click(function () {

        if (jQuery(this).prop('checked')) {
            jQuery('#howmanycigs').css('display', 'table');
        }
        else {
            jQuery('#howmanycigs').css('display', 'none');
        }
    });

    jQuery('#paymethod').change(function () {
        if (jQuery(this).val() == 'Monthly Direct Debit') {

            jQuery('#payWhenDiv').show();
            jQuery('#playermempaymonthly').show();
            jQuery('#playermempaysingle').hide();
            jQuery('#playermempaysingle').find('select').val('');
            jQuery('#supportermempaymonthly').show();
            jQuery('#supportermempaysingle').hide();
            jQuery('#supportermempaysingle').find('select').val('');

        }
        else if (jQuery(this).val() == 'Single Payment') {
            jQuery('#payWhenDiv').hide();
            jQuery('#payWhen').find('select').val('');
            jQuery('#playermempaymonthly').hide();
            jQuery('#playermempaymonthly').find('select').val('');
            jQuery('#playermempaysingle').show();
            jQuery('#supportermempaymonthly').hide();
            jQuery('#supportermempaymonthly').find('select').val('');
            jQuery('#supportermempaysingle').show();
        }
        else {
            jQuery('#payWhenDiv').hide();
            jQuery('#payWhen').find('select').val('');
            jQuery('#playermempaymonthly').hide();
            jQuery('#playermempaymonthly').find('select').val('');
            jQuery('#playermempaysingle').hide();
            jQuery('#playermempaysingle').find('select').val('');
            jQuery('#supportermempaymonthly').hide();
            jQuery('#supportermempaymonthly').find('select').val('');
            jQuery('#supportermempaysingle').hide();
            jQuery('#supportermempaysingle').find('select').val('');

        }

    });


    jQuery('#injuredyesno').change(function () {
        if (jQuery(this).val() == 'Yes') {
            jQuery('#injuriesfieldset').show();
        }
        else {
            jQuery('#injuriesfieldset').hide();
        }
    });

    jQuery('#playedbefore').change(function () {
        if (jQuery(this).val() == 'Yes') {
            jQuery('#howmanyseasonsgroup').css('display', 'table');
        }
        else {
            jQuery('#howmanyseasonsgroup').css('display', 'none');
        }
    });


    jQuery('#conddisablefieldset').find('.addrow').click(function () {

    });

    jQuery('#conddisablefieldset').find('.removerow').click(function () {


        if (jQuery('#conddisablefieldset').find('tbody').find('tr').length > 2) {
            jQuery('#conddisablefieldset').find('tbody tr:last-child').remove();
        }
        else if (jQuery('#conddisablefieldset').find('tbody').find('tr').length == 2) {
            jQuery('#conddisablefieldset').find('tbody tr:last-child').remove();
            jQuery(this).hide();
        }
        return false;
    });

    jQuery('#allergiesfieldset').find('.addrow').click(function () {

        var rownum = jQuery('#allergiesfieldset').find('tbody').find('tr').length + 1;

        var row = '<tr><td><input class=\"required tableInputs\" name="allergies_name_row' + rownum + '" type=\'text\' /></td><td>'
            + '<input class=\"required tableInputs\" name="allergies_drugname_row' + rownum + '" type=\'text\' /></td><td> '
            + '<input class=\"required tableInputs\" name="allergies_drugdose_freq_row' + rownum + '" type=\'text\' /></td>'
            + '</tr>';
        jQuery('#allergiesfieldset').find('tbody').append(row);

        if (jQuery('#allergiesfieldset').find('tbody').find('tr').length > 1) {
            jQuery('#allergiesfieldset').find('.removerow').show();
        }
        return false;

    });

    jQuery('#allergiesfieldset').find('.removerow').click(function () {


        if (jQuery('#allergiesfieldset').find('tbody').find('tr').length > 2) {
            jQuery('#allergiesfieldset').find('tbody tr:last-child').remove();
        }
        else if (jQuery('#allergiesfieldset').find('tbody').find('tr').length == 2) {
            jQuery('#allergiesfieldset').find('tbody tr:last-child').remove();
            jQuery(this).hide();
        }
        return false;
    });

    jQuery('#injuriesfieldset').find('.addrow').click(function () {

        var rownum = jQuery('#injuriesfieldset').find('tbody').find('tr').length + 1;

        var row = '<tr><td><input class="required tableInputs" name="injuries_name_row' + rownum + '" type=\'text\' /></td><td>'
            + '<input class="required tableInputs" name="injuries_when_row' + rownum + '" type=\'text\' /></td><td> '
            + '<input class="required tableInputs" name="injuries_treatmentreceived_row' + rownum + '" type=\'text\' /></td><td>'
            + '<input class="required tableInputs" name="injuries_who_row' + rownum + '" type=\'text\' /></td><td>'
            + '<input class="required tableInputs" name="injuries_status_row' + rownum + '" type=\'text\' /></td>';
        +'</tr>';
        jQuery('#injuriesfieldset').find('tbody').append(row);

        if (jQuery('#injuriesfieldset').find('tbody').find('tr').length > 1) {
            jQuery('#injuriesfieldset').find('.removerow').show();
        }
        return false;
    });

    jQuery('#injuriesfieldset').find('.removerow').click(function () {


        if (jQuery('#injuriesfieldset').find('tbody').find('tr').length > 2) {
            jQuery('#injuriesfieldset').find('tbody tr:last-child').remove();
        }
        else if (jQuery('#injuriesfieldset').find('tbody').find('tr').length == 2) {
            jQuery('#injuriesfieldset').find('tbody tr:last-child').remove();
            jQuery(this).hide();
        }
        return false;
    });


    jQuery('#newpaymentbutton').click(function () {
        jQuery('#newpayment').val('true');
        jQuery('#membershipform_payment').submit();
    });

});