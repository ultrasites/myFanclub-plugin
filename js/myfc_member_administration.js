jQuery(function () {

    /**
     * Collapsible Button
     */
    jQuery('.wp-fanclub-members-data__collapsible-button').on('click', function (e) {
        $dataTarget = jQuery(e.currentTarget).attr('data-target');
        $targetElement = jQuery('#' + $dataTarget);

        if ($targetElement.is(':visible')) {
            $targetElement.slideUp();
            jQuery(e.currentTarget).find('.chevron.up.icon').removeClass('up').addClass('down');
        } else {
            $targetElement.slideDown();
            jQuery(e.currentTarget).find('.chevron.down.icon').removeClass('down').addClass('up');
        }
    });

    /**
     * Modals
     */

    jQuery('button[trigger-modal]').on('click', function (e) {

        $modalName = jQuery(e.currentTarget).attr('trigger-modal');
        jQuery('#' + $modalName).modal({
            blurring: true,
            inverted: true,
            closable: true,
            onVisible: function () {
                jQuery('#addStart, #addBirthday, #editStart, #editBirthday').calendar({
                    text: {
                        days: ['So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'],
                        months: ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Otkober', 'November', 'Dezember'],
                        monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dez'],
                        today: 'Heute',
                        now: 'Jetzt',
                        am: 'AM',
                        pm: 'PM'
                    },
                    formatter: {
                        date: function (date, settings) {
                            if (!date) return '';
                            var day = date.getDate();
                            var month = date.getMonth() + 1;

                            if (day < 10) {
                                day = '0' + day;
                            }
                            if (month < 10) {
                                month = '0' + month;
                            }

                            var year = date.getFullYear();
                            return day + '.' + month + '.' + year;
                        }
                    },
                    type: 'date',
                    today: true,
                    closable: true,
                    verbose: true
                });
            }
        }).modal('show');

    });

    jQuery('.dropdown').on('click', function (e) {
        jQuery(e.currentTarget).dropdown({
            onChange: function (value, text, $selectedItem) {
                jQuery('#wp-fanclub-modalAdd-data').slideDown();
            }
        }).focus();

    });

    jQuery('#preferences .item').tab();

    /**
     * Member search
     */
    jQuery('#memberSearch').keyup(function (e) {
        $searchValue = jQuery('#memberSearch').val();

        $table = jQuery('#memberData');

        if ($searchValue === "") {
            $table.find('.wp-fanclub-members-data__fullname').each(function (i, elem) {
                jQuery(elem).parent().parent().show();
            });
        } else {

            $table.find('.wp-fanclub-members-data__fullname').each(function (i, elem) {

                var fullname = elem.attributes.forename+" "+elem.attributes.lastname;

                if (elem.attributes.forename.nodeValue.indexOf($searchValue) >= 0
                    || fullname.indexOf($searchValue) >= 0
                    || elem.attributes.lastname.nodeValue.indexOf($searchValue) >= 0) {
                    jQuery(elem).parent().parent().show();
                } else {
                    jQuery(elem).parent().parent().hide();
                }
            }, this);

        }


    });


});
