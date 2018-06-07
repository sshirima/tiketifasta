var firstSeatLabel = 1;
$(document).ready(function() {

    var $cart = $('#selected-seats'),
        $counter = $('#counter'),
        $total = $('#total'),
        sc = $('#seat_map').seatCharts({

            map: seat_arrangement.split(",")
            ,
            seats: {
                f: {
                    price   : 0,
                    classes : 'first-class', //your custom CSS class
                    category: 'First Class'
                },
                e: {
                    price   : 0,
                    classes : 'economy-class', //your custom CSS class
                    category: 'Economy Class'
                }

            },
            naming : {
                top : false,
                getLabel : function (character, row, column) {
                    var label = seats[firstSeatLabel].name;
                    firstSeatLabel++;
                    return label;
                },
            },
            legend : {
                node : $('#legend'),
                items : [
                    [ 'f', 'available',   'First Class' ],
                    [ 'e', 'available',   'Economy Class'],
                    [ 'f', 'unavailable', 'Already Booked']
                ]
            },
        });
});

function recalculateTotal(sc) {
    var total = 0;

    //basically find every selected seat and sum its price
    sc.find('selected').each(function () {
        total += this.data().price;
    });

    return total;
}
