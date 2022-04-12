$(document).ready(function(){
	$('.profile-section').hide();
	$('.profile-section:first').show();
	if($('#paypal').attr('data-show-initially') == '1'){
		$('.profile-section').hide();
		$('#paypal').show();
	}
	if($('#upgrades').attr('data-show-initially') == '1'){
		$('.profile-section').hide();
		$('#upgrades').show();
	}
	$('.profile-nav-link').on('click',function(){
		let section = $(this).attr('id').substr(4);
		$('.profile-nav-link').removeClass('tab-active');
		$(this).addClass('tab-active');
		$('.profile-section').hide();
		$('#'+section).show();
	});
	$('.membership_level').on('change',function(){
		$('.membership_level').prop('checked', false);
		$(this).prop('checked', true);
	});
});