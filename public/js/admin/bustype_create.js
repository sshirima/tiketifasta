
$(document).ready(function() {
    $('#btn-check-layout').on('click', function () {
        drawSeatLayout();

    });

    $('#select_arrangement').change(function () {
        $('#seat_arrangement').val('')
        drawSeatLayout()
    });
});

function drawSeatLayout(){
    var firstSeatLabel = 1;
    $('.bus-seats div:first').remove();
    $('.seat-legend div:first').remove();

    $('.bus-seats').html('<div ><div id="seat-map"><h4 class="front-indicator">Front</h4></div></div>');
    $('.seat-legend').append('<div id="legend"></div>');

    var seat_mapping = [];
    var row_arrangement = '';
    var selected_arrangement = $('#select_arrangement').val();
    var number_of_seats = $('#seats').val();
    if (selected_arrangement === '0' || number_of_seats ===''){
        $('#seat-map').html('Seat arrangement or number of seats not selected...');
    } else{
        var col1 = parseInt(selected_arrangement.substring(0, 1));
        var col2 = parseInt(selected_arrangement.substring(2, 3));

        if ($('#seat_arrangement').val() === ''){
            for (k = 0; k < col1; k++) {
                row_arrangement = row_arrangement+'e';
            }
            row_arrangement = row_arrangement+'_';
            for (k = 0; k < col2; k++) {
                row_arrangement = row_arrangement+'e';
            }
            var rows = parseInt(number_of_seats/(col1+col2));

            for (i = 0; i < rows; i++) {
                seat_mapping[i] = row_arrangement;
            }
            $('#seat_arrangement').val(seat_mapping.toString().replace(/,/gi,',\n'));
        } else{
            seat_mapping = $('#seat_arrangement').val().split(",");
        }
    }

    var $cart = $('#selected-seats'),
        $counter = $('#counter'),
        $total = $('#total'),
        sc = $('#seat-map ').seatCharts({
            map: seat_mapping,
            seats: {
                f: {
                    price   : 100,
                    classes : 'first-class', //your custom CSS class
                    category: 'First Class'
                },
                e: {
                    price   : 40,
                    classes : 'economy-class', //your custom CSS class
                    category: 'Economy Class'
                }

            },
            naming : {
                top : false,
                getLabel : function (character, row, column) {
                    return firstSeatLabel++;
                },
            },
            legend : {
                node : $('#legend'),
                items : [
                    [ 'f', 'available',   '{f}First Class' ],
                    [ 'e', 'available',   '{e}Economy Class']
                ]
            },
            click: function () {
                if (this.status() == 'available') {
                    //let's create a new <li> which we'll add to the cart items
                    $('<li>'+this.data().category+' Seat # '+this.settings.label+': <b>$'+this.data().price+'</b> <a href="#" class="cancel-cart-item">[cancel]</a></li>')
                        .attr('id', 'cart-item-'+this.settings.id)
                        .data('seatId', this.settings.id)
                        .appendTo($cart);

                    /*
                     * Lets up<a href="https://www.jqueryscript.net/time-clock/">date</a> the counter and total
                     *
                     * .find function will not find the current seat, because it will change its stauts only after return
                     * 'selected'. This is why we have to add 1 to the length and the current seat price to the total.
                     */
                    $counter.text(sc.find('selected').length+1);
                    $total.text(recalculateTotal(sc)+this.data().price);
                    return 'selected';
                } else if (this.status() == 'selected') {
                    //update the counter
                    $counter.text(sc.find('selected').length-1);
                    //and total
                    $total.text(recalculateTotal(sc)-this.data().price);

                    //remove the item from our cart
                    $('#cart-item-'+this.settings.id).remove();

                    //seat has been vacated
                    return 'available';
                } else if (this.status() == 'unavailable') {
                    //seat has been already booked
                    return 'unavailable';
                } else {
                    return this.style();
                }
            }
        });
    //this will handle "[cancel]" link clicks
    /*$('#selected-seats').on('click', '.cancel-cart-item', function () {
        //let's just trigger Click event on the appropriate seat, so we don't have to repeat the logic here
        sc.get($(this).parents('li:first').data('seatId')).click();
    });*/

    /*//let's pretend some seats have already been booked
    sc.get(['1_2', '4_1', '7_1', '7_2']).status('unavailable');*/

    $('#btn-check-layout').html('Refresh layout');

    $('#seats').val(getTotalSeats(sc));
}

function getTotalSeats(sc) {
    var total = 0;

    //basically find every selected seat and sum its price
    sc.find('available').each(function () {
        //total += this.data().price;
        total += 1;
    });

    return total;
}
