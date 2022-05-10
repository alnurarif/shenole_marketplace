$(document).ready(function(){
	$('.shopping-cart-modal').hide();
    $('#cart-icon-container').on('click',function(){
        $('.shopping-cart-modal').fadeIn();
    });
    $('#close-shopping-cart').on('click', function(){
        $('.shopping-cart-modal').fadeOut();
    });
});