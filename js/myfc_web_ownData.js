/*
 * Copyright 2017 Ultra Sites Medienagentur.
 * http://www.ultra-sites.de
 */
jQuery(function () {



    localStorage.setItem('lastname', jQuery('#lastname').val());
    localStorage.setItem('forename', jQuery('#forename').val());
    localStorage.setItem('street', jQuery('#street').val());
    localStorage.setItem('housenumber', jQuery('#housenumber').val());
    localStorage.setItem('plz', jQuery('#plz').val());
    localStorage.setItem('city', jQuery('#city').val());
    localStorage.setItem('email', jQuery('#email').val());
    localStorage.setItem('phone', jQuery('#phone').val());
    localStorage.setItem('birthday', jQuery('#birthday').val());

    isAllElementsChanged();


    /**
     * Listen if are differences between old and new value
     */
    jQuery('#myfc_ownData_editBlock input[type="text"]').keyup(function () {
        var $this = jQuery(this);

        if (isChanged($this.attr('id'), $this.val())) {
            $this.addClass('myfc_ownData_input_edited');
            localStorage.setItem('flag', 'true');
        } else {
            $this.removeClass('myfc_ownData_input_edited');
        }

        isAllElementsChanged();

    });

});

/**
 * Checks if localStorage item is changed
 */
function isChanged(id, input) {
    return localStorage.getItem(id) !== input;
}

/**
 * Enable or disable the save button
 */
function isAllElementsChanged() {

    for (var i = 0; i < localStorage.length; i++) {

        var value = localStorage.key(i);
        if (jQuery('#' + value).hasClass('myfc_ownData_input_edited')) {
            jQuery('#myfc_ownData_submitBtn').removeAttr('disabled');
            break;
        } else {
            jQuery('#myfc_ownData_submitBtn').attr('disabled','disabled');
        }
    }
}