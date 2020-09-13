jQuery(document).ready(function ($) {
    dzscal_init("#traurora", {
        design_transitionDesc: 'slide'
        , header_weekdayStyle: 'three'
        , design_transition: 'fade'
    });

    var options = {
        minimum: 1,
        maximize: 10,

        onMinimum: function(e) {
            console.log('reached minimum: '+e)
        },
        onMaximize: function(e) {
            console.log('reached maximize'+e)
        }
    }

    $('#handleCounter').handleCounter(options);

});

function myBooking(event){
    var time = event.target.getAttribute('time');
    var date = event.target.getAttribute('date');

    document.getElementById("date-time").value = time;
    document.getElementById("date-booking").value = date;
    
    var newDate = date.split("-");

    var day = newDate[2];
    var month = newDate[1];
    var year = newDate[0];
    
    var full_date = day + "-" + month + "-"+ year;

    document.getElementById("text-info-booking").innerHTML = time + " " + full_date;

}

