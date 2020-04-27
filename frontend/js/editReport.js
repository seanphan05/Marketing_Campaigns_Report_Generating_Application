$(document).ready(function () {
    // Ajax call functions
    function yearAjax (action, json) {
        $.ajax({
            method: "POST",
            url: "/php/editReport/getMonthAjax.php",
            data: json,
            beforeSend: function() {
                $("#success_message").hide();

            },
            success: function(data) {
                $("#select_month").show();
                if (action === 'add') {
                    $("#monthAdd").html(data);
                } else {
                    $("#monthChange").html(data);
                }
                $("#submit_details").hide();
                $("#select_promo").hide();
            }
        });
    }

    function monthAjax (action, json) {
        $.ajax({
            method: "POST",
            url: "/php/editReport/getPromoNameAjax.php",
            data: json,
            beforeSend: function() {
                $("#success_message").hide();

            },
            success: function(data) {
                $("#select_promo").show();
                if (action === 'add') {
                    $("#promoNameAdd").html(data);
                } else {
                    $("#promoNameChange").html(data);
                }

                $("#submit_details").hide();
            }
        });
    }

    // Select Year (Edit Report)
    $("#yearAdd").change(function() {
        var year = $("#yearAdd").val();
        var action = 'add';
        var json = {action: action, year: year};
        yearAjax(action, json)
    });

    $("#yearChange").change(function() {
        var year = $("#yearChange").val();
        var action = 'change';
        var json = {action: action, year: year};
        yearAjax(action, json)
    });


    // Select Month (Edit Report)
    $("#monthAdd").change(function() {
        var year = $("#yearAdd").val();
        var month = $("#monthAdd").val();
        var action = 'add';
        var json = {action: action, year: year, month: month};
        monthAjax(action, json)
    });

    $("#monthChange").change(function() {
        var year = $("#yearChange").val();
        var month = $("#monthChange").val();
        var action = 'change';
        var json = {action: action, year: year, month: month};
        monthAjax(action, json)
    });


    // Select Promo Name (Edit Report)
    $('#promoNameAdd').change(function() {
        $("#submit_details").show();
    });
    $('#promoNameChange').change(function() {
        var year = $("#yearChange").val();
        var month = $("#monthChange").val();
        var promo = $("#promoNameChange").val();
        var json = {year: year, month: month, promo: promo};
        $.ajax({
            method: "POST",
            url: "/php/editReport/getCampaignDetailsAjax.php",
            data: json,
            beforeSend: function() {
                $("#success_message").hide();
            },
            success: function(data) {
                $("#current_camp_details").html(data);
            }
        });
        $("#submit_details").show();
    });


    // Add Campaign Details
    $("#add_details_submit").off('click');
    $("#add_details_submit").one('click', function (e) {
        e.preventDefault();
        var month = $("#monthAdd").val();
        var year = $("#yearAdd").val();
        var promo = $("#promoNameAdd").val();
        var details = $("#campDetailAdd").val();
        var action = 'add';
        var json = {action: action, editMonth: month, editYear: year, editPromoName: promo, editCampaignDetails: details};
        $.ajax({
            method: "POST",
            url: "/php/editReport/editCampaignDetailsAjax.php",
            data: json,
            beforeSend: function() {
                $("#success_message").hide();

            },
            success: function (data) {
                $("#success_message").show();
                $("#camp_details_result").html(data);
            }
        })
    });

    // Change Campaign Details
    $("#change_details_submit").off('click');
    $("#change_details_submit").one('click', function (e) {
        e.preventDefault();
        var month = $("#monthChange").val();
        var year = $("#yearChange").val();
        var promo = $("#promoNameChange").val();
        var details = $("#campDetailChange").val();
        var action = 'change';
        var json = {action: action, editMonth: month, editYear: year, editPromoName: promo, editCampaignDetails: details};
        $.ajax({
            method: "POST",
            url: "/php/editReport/editCampaignDetailsAjax.php",
            data: json,
            beforeSend: function() {
                $("#success_message").hide();

            },
            success: function (data) {
                $("#success_message").show();
                $("#camp_details_result").html(data);
            }
        })
    });

    // Delete Campaign Details
    $("#delete_details_submit").off('click');
    $("#delete_details_submit").one('click', function (e) {
        e.preventDefault();
        var month = $("#monthChange").val();
        var year = $("#yearChange").val();
        var promo = $("#promoNameChange").val();
        var action = 'delete';
        var json = {action: action, editMonth: month, editYear: year, editPromoName: promo};
        $.ajax({
            method: "POST",
            url: "/php/editReport/editCampaignDetailsAjax.php",
            data: json,
            beforeSend: function() {
                $("#success_message").hide();

            },
            success: function (data) {
                $("#success_message").show();
                $("#camp_details_result").html(data);
            }
        })
    });
});
