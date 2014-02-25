$(document).ready(function() 
{
	$('[data-toggle=offcanvas]').click(function() {
	$('.row-offcanvas').toggleClass('active');
	});
	
	
	$('#myCarousel').carousel({
	interval: 10000
	})
	
	$('#myCarousel').on('slid.bs.carousel', function() {
		//alert("slid");
	});
    
    
});	
	