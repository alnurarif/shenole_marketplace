$(document).ready(function(){
	setDescription($('#description_textarea').val());

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

	$('#description_textarea').on('keyup',function(){
		let textarea_value = $(this).val();
		setDescription(textarea_value);
	});
});
function setDescription(textarea_value){
	let maximum_characters = 2000;
	let left_characters = maximum_characters;
	let textarea_value_length = textarea_value.length;
	left_characters -= textarea_value_length;
	if(left_characters > 0){
		$('#vendor-description-character-countdown').text(left_characters);

		var lines = textarea_value.split(/\r?\n/);
		// create html
		var html="";
		for(var i=0;i<lines.length;i++){
		    html+='<p>'+lines[i]+'</p>';
		}
		html = html.replace("<p></p>", "");
		$('#description_with_html_tags').val(html);
	}
	if(left_characters <= 0){
		$('#vendor-description-character-countdown').text(0);
		textarea_value = textarea_value.substr(0,2000);
		$('#description_textarea').val(textarea_value);
		var lines = textarea_value.split(/\r?\n/);
		// create html
		var html="";
		for(var i=0;i<lines.length;i++){
		    html+='<p>'+lines[i]+'</p>';
		}
		html = html.replace("<p></p>", "");
		$('#description_with_html_tags').val(html);
	}
}