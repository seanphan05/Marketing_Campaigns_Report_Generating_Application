$(document).ready(function () {
    // View Monthly and Campaign breakdown report
    $("#viewMonthCampaign").one('click',function (e) {
        e.preventDefault();
        var url = $('#url').val().toString();
        var year = $("#selectedYear").val();
        var json = {selectedYear: year};
        $.ajax({
            method: "POST",
            url: url,
            data: json,
            success: function (data) {
                console.log(url);
                $("#result").html(data);
            }
        })
    });

    // View Subscriber List Report
    $("#viewSubscriberList").one(' click',function (e) {
        e.preventDefault();
        var month = $("#selectedMonth").val();
        var year = $("#selectedYear").val();
        var json = {selectedMonth: month, selectedYear: year};
        $.ajax({
            method: "POST",
            url: "/php/viewReport/bySubscriberListAjax.php",
            data: json,
            success: function (data) {
                $("#result").html(data);
            }
        })
    });

    // Refresh button
    $("#refresh").on('click', function () {
        $.ajax({
            method: "POST",
            url: "/php/refreshData.php",
            beforeSend: function () {
                $("#before").show();
                $("#after").hide();
            },
            success: function (data) {
                $("#before").hide();
                $("#after").show();
                $("#after").html(data);
                $("#refresh").show();
            }
        });
    });
});
