$(document).ready(function(){
	$('.profile-section').hide();
	$('.profile-section:first').show();
	$('.tab').on('click',function(){
		let section = $(this).attr('id').substr(4);
		console.log(section);
		$('.tab').removeClass('tab-active');
		$(this).addClass('tab-active');
		$('.profile-section').hide();
		$('#'+section).show();
	});
});