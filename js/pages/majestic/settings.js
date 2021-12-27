$(document).ready(function(){
	$('.profile-section').hide();
	$('.profile-section:first').show();
	$('.profile-nav-link').on('click',function(){
		let section = $(this).attr('id').substr(4);
		console.log(section);
		$('.profile-nav-link').removeClass('tab-active');
		$(this).addClass('tab-active');
		$('.profile-section').hide();
		$('#'+section).show();
	});
});