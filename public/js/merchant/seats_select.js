var firstSeatLabel = 1;
$(document).ready(function() {

    var $cart = $('#selected-seats'),
        $counter = $('#counter'),
        $total = $('#total'),
        sc = $('#seat-map').seatCharts({
            map: seatArrangement.split(",")
            ,
            seats: {
                f: {
                    price   : 0,
                    classes : 'first-class', //your custom CSS class
                    category: 'First Class'
                },
                e: {
                    price   : ticketPrices,
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
            click: function () {
                if (this.status() == 'available') {
                    //let's create a new <li> which we'll add to the cart items
                    if ((sc.find('selected').length) == 0){
                    $('<li>'+this.data().category+' Seat # '+this.settings.label+': <b>'+this.data().price+' (Tsh)</b> <a href="#" class="cancel-cart-item">[cancel]</a></li>')
                        .attr('id', 'cart-item-'+this.settings.id)
                        .attr('name', this.settings.label)
                        .data('seatId', this.settings.id)
                        .appendTo($cart);
                    $('<input name="seat" value="'+this.settings.label+'" hidden id="'+this.settings.label+'">').appendTo($cart);
                    /*
                     * Lets up<a href="https://www.jqueryscript.net/time-clock/">date</a> the counter and total
                     *
                     * .find function will not find the current seat, because it will change its stauts only after return
                     * 'selected'. This is why we have to add 1 to the length and the current seat price to the total.
                     */
                    $counter.text(sc.find('selected').length+1);
                    $total.text(recalculateTotal(sc)+this.data().price);
                    //Enable submit button

                        $(':input[type="submit"]').prop('disabled', false);
                        return 'selected';
                    } else {
                        return 'available';
                    }

                } else if (this.status() == 'selected') {
                    //update the counter
                    $counter.text(sc.find('selected').length-1);
                    //and total
                    $total.text(recalculateTotal(sc)-this.data().price);
                    //Remove seat input field
                    $('#'+this.settings.label+'').remove();
                    //remove the item from our cart
                    $('#cart-item-'+this.settings.id).remove();
                    //Disable submit button
                    if ((sc.find('selected').length-1) == 0){
                        $(':input[type="submit"]').prop('disabled', true);
                    }
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
    $('#selected-seats').on('click', '.cancel-cart-item', function () {
        //let's just trigger Click event on the appropriate seat, so we don't have to repeat the logic here
        sc.get($(this).parents('li:first').data('seatId')).click();
    });

    /*sc.status('1_2', 'unvailable');
    //let's pretend some seats have already been booked*/
    //sc.get(['3_1']).status('unavailable');
    $.each(seats, function( index, value ) {
        if (value.status === 'Unavailable'||value.status === 'unavailable'){
            sc.get([value.row+'_'+value.column]).status('unavailable');
        }
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
