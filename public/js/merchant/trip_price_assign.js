$( document ).ready(function () {

    $('button[name*=set-price-]').on('click', function () {

        var id = $(this).val();
        //Remove label
        $('.trip-prices-'+id).remove();

        var prices = document.getElementById('prices-'+id);

        var trip_price = document.createElement("div");

        trip_price.setAttribute("class", "input-group");

        trip_price.innerHTML = ('<input class="form-control" type="number" id="price-value-'+id+'" placeholder="Price">\n' +
            '                       <div class="input-group-btn">\n' +
            '                      <button class="btn btn-success" onclick="savePrice('+ id +');">Save</button>\n' +
            '                  </div>');

        prices.appendChild(trip_price);

        $(this).remove();

    });

    $('button[name*=update-price-]').on('click', function () {

        var id = $(this).val();

        $('.trip-prices-'+id).prop('disabled',false);

        $(this).removeClass('btn-primary').addClass('btn-success').html('Save').attr('onclick','savePrice('+ id +');');
    });

});

function savePrice(tripId) {

    var price = $('#price-value-'+tripId).val();

    if (price === ''){
        $.toaster({ priority : 'error', title : 'Error', message : 'Price not specified'});
    }else {
        $.toaster({ priority : 'success', title : 'Success', message : 'Saving=>'+price});

        $.ajax({
            type: 'POST',
            url: 'trip/'+tripId+'/save',
            data: {
                'price-value': $('input[id=price-value-'+tripId+']').val(),
                '_token': $('input[name=_token]').val()
            },
            success: function(trip, textStatus, xhr){
                $.toaster({ priority : 'success', title : 'Success', message : 'Updated success'});
                $('button[name=update-price-'+tripId+']').removeClass('btn-success').addClass('btn-primary').html('Update')
                $('input[id=price-value-'+tripId+']').prop('disabled',true);
            },
            statusCode: {
                404: function() {
                    $.toaster({ priority : 'error', title : 'Failed', message : 'Route not found'});
                },
                500: function() {
                    $.toaster({ priority : 'error', title : 'Failed', message : 'Select a route'});
                }
            }
        });
    }
}