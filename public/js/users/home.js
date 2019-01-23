$(document).ready(function () {
    var date = new Date();
    var currentMonth = date.getMonth();
    var currentDate = date.getDate();
    var currentYear = date.getFullYear();

    $('#datepicker').datepicker({
        minDate: new Date(currentYear, currentMonth, currentDate),
        dateFormat: 'yy-mm-dd'
    });
});

/*
$(function()
{
    $( "#from" ).autocomplete({
        source: "auto-complete-query",
        minLength: 3,
        select: function(event, ui) {
            $('#from').val(ui.item.value);
        }
    });

    $( "#to" ).autocomplete({
        source: "auto-complete-query",
        minLength: 3,
        select: function(event, ui) {
            $('#to').val(ui.item.value);
        }
    });
});*/
