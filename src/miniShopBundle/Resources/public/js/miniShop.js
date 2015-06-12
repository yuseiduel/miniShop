    $(document).ready(function() {
        $('.addToCart').click(function() {
            var path = $(this).attr("data-path");
            $.post( path, function( data ) {
                $('#shoppingCart').html(data);
            });
        });
    });