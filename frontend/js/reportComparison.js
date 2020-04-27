$(document).ready(function () {
    // Ajax call functions
    function getCompYear(json, selectMonthId, getCompMonthId, compReportResultId) {
            $.ajax({
                method: "POST",
                url: "/php/reportComparison/getCompMonthAjax.php",
                data: json,
                success: function(data) {
                    $(compReportResultId).html(data);
                    $.ajax({
                        method: "POST",
                        url: "/php/editReport/getMonthAjax.php",
                        data: json,
                        success: function(data) {
                            $(selectMonthId).show();
                            $(getCompMonthId).html(data);
                        }
                    });
                }
            });
        }

    function getCompMonth(json, compReportResultId) {
            $.ajax({
                method: "POST",
                url: "/php/reportComparison/getCompMonthAjax.php",
                data: json,
                success: function(data) {
                    $(compReportResultId).html(data);
                }
            });
    }

    // Get Comparison Years
    $("#getCompYear1").change(function() {
        var year = $("#getCompYear1").val();
        var action = 'compare';
        var json = {action: action, compYear: year};
        getCompYear(json, "#select_month1", "#getCompMonth1", "#comp_report1_result")
    });

    $("#getCompYear2").change(function() {
        var year = $("#getCompYear2").val();
        var action = 'compare';
        var json = {action: action, compYear: year};
        getCompYear(json, "#select_month2", "#getCompMonth2", "#comp_report2_result")
    });

    // Get Comparison Months
    $("#getCompMonth1").change(function() {
        var year = $("#getCompYear1").val();
        var month = $("#getCompMonth1").val();
        var action = 'compare';
        var json = {action: action, compYear: year, compMonth: month};
        getCompMonth(json, "#comp_report1_result")
    });

    $("#getCompMonth2").change(function() {
        var year = $("#getCompYear2").val();
        var month = $("#getCompMonth2").val();
        var action = 'compare';
        var json = {action: action, compYear: year, compMonth: month};
        getCompMonth(json, "#comp_report2_result")
    });
});