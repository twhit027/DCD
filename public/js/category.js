$('.dcd-expand-text').click(function(){
	$('#dcd-short-'+$(this).data("id")).slideToggle('slow');
	$('#dcd-content-'+$(this).data("id")).slideToggle('slow');
	
	return false;
});