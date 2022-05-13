$(document).ready(function(){
	$('.profile-section').hide();
	$('.profile-section:first').show();
	if($('#memberships').attr('data-show-initially') == '1'){
		$('.profile-section').hide();
		$('#memberships').show();
	}
	if($('#paypal').attr('data-show-initially') == '1'){
		$('.profile-section').hide();
		$('#paypal').show();
	}
	if($('#ads').attr('data-show-initially') == '1'){
		$('.profile-section').hide();
		$('#ads').show();
	}
	$('.profile-nav-link').on('click',function(){
		let section = $(this).attr('id').substr(4);
		console.log(section);
		$('.profile-nav-link').removeClass('tab-active');
		$(this).addClass('tab-active');
		$('.profile-section').hide();
		$('#'+section).show();
	});

	$('#add_keyword_info_to_list').on('click', function(e){
		e.preventDefault();
		let keyword_quantity = $('#keyword_quantity').val();
		let keyword_price = $('#keyword_price').val();
		
		$('#keyword_quantity').val('');
		$('#keyword_price').val('');
		
		//last two is show only city and state, show the number
		let full_info_joined = `${keyword_quantity}|||${keyword_price}`;

		let keyword_quantity_price_list = $('#added_keyword_quantity_pricing_list').find('.single_added_keyword_quantity_pricing').length;
		let element_to_create = '';
		let input_to_create = '';

		if(keyword_quantity_price_list >0){
			element_to_create += `<li class="category-li single_added_keyword_quantity_pricing" id="single_added_keyword_quantity_pricing_${(keyword_quantity_price_list+1)}">
				<div class="display_none keyword_quantity_pricing_object">
					<span class="keyword_quantity">${keyword_quantity}</span>
					<span class="keyword_price">${keyword_price}</span>
				</div>
				<div>
					<span>${keyword_quantity}</span> keywords | 
					<span>$${keyword_price}</span>
				</div>
				<br>
				<div class="delete_button_container"><button type="button" class="small-button primary white-text delete_keyword_quantity_pricing_from_list" id="delete_keyword_quantity_pricing_${(keyword_quantity_price_list+1)}">Delete</button></div>	
			</li>`;
			input_to_create += '<input type="hidden" value="'+full_info_joined+'" name="keyword_quantity_price_infos[]" id="keyword_quantity_price_infos_input_'+(keyword_quantity_price_list+1)+'">';
		}else{
			element_to_create += `<li class="category-li single_added_keyword_quantity_pricing" id="single_added_keyword_quantity_pricing_1">
				<div class="display_none keyword_quantity_pricing_object">
					<span class="keyword_quantity">${keyword_quantity}</span>
					<span class="keyword_price">${keyword_price}</span>
				</div>
				<div>
					<span>${keyword_quantity}</span> keywords | 
					<span>$${keyword_price}</span>
				</div>
				<br>
				<div class="delete_button_container"><button type="button" class="small-button primary white-text delete_keyword_quantity_pricing_from_list" id="delete_keyword_quantity_pricing_1">Delete</button></div>	
			</li>`;
			input_to_create += '<input type="hidden" value="'+full_info_joined+'" name="keyword_quantity_price_infos[]" id="keyword_quantity_price_infos_input_1">';
		}
		$('#added_keyword_quantity_pricing_list').append(element_to_create);
		$('#hidden_keyword_quantity_price_input').append(input_to_create);
	});
	$(document).on('click','.delete_keyword_quantity_pricing_from_list',function(e){
		e.preventDefault();
		let row_number = $(this).attr('id').substr(32);
		$('#keyword_quantity_price_infos_input_'+row_number).remove();
		$('#single_added_keyword_quantity_pricing_'+row_number).remove();
		rearrange_keyword_quantity_pricing_list();
	});

	$('#add_category_info_to_list').on('click', function(e){
		e.preventDefault();
		let category_quantity = $('#category_quantity').val();
		let category_price = $('#category_price').val();
		
		$('#category_quantity').val('');
		$('#category_price').val('');
		
		//last two is show only city and state, show the number
		let full_info_joined = `${category_quantity}|||${category_price}`;

		let category_quantity_price_list = $('#added_category_quantity_pricing_list').find('.single_added_category_quantity_pricing').length;
		let element_to_create = '';
		let input_to_create = '';

		if(category_quantity_price_list >0){
			element_to_create += `<li class="category-li single_added_category_quantity_pricing" id="single_added_category_quantity_pricing_${(category_quantity_price_list+1)}">
				<div class="display_none category_quantity_pricing_object">
					<span class="category_quantity">${category_quantity}</span>
					<span class="category_price">${category_price}</span>
				</div>
				<div>
					<span>${category_quantity}</span> categorys | 
					<span>$${category_price}</span>
				</div>
				<br>
				<div class="delete_button_container"><button type="button" class="small-button primary white-text delete_category_quantity_pricing_from_list" id="delete_category_quantity_pricing_${(category_quantity_price_list+1)}">Delete</button></div>	
			</li>`;
			input_to_create += '<input type="hidden" value="'+full_info_joined+'" name="category_quantity_price_infos[]" id="category_quantity_price_infos_input_'+(category_quantity_price_list+1)+'">';
		}else{
			element_to_create += `<li class="category-li single_added_category_quantity_pricing" id="single_added_category_quantity_pricing_1">
				<div class="display_none category_quantity_pricing_object">
					<span class="category_quantity">${category_quantity}</span>
					<span class="category_price">${category_price}</span>
				</div>
				<div>
					<span>${category_quantity}</span> categorys | 
					<span>$${category_price}</span>
				</div>
				<br>
				<div class="delete_button_container"><button type="button" class="small-button primary white-text delete_category_quantity_pricing_from_list" id="delete_category_quantity_pricing_1">Delete</button></div>	
			</li>`;
			input_to_create += '<input type="hidden" value="'+full_info_joined+'" name="category_quantity_price_infos[]" id="category_quantity_price_infos_input_1">';
		}
		$('#added_category_quantity_pricing_list').append(element_to_create);
		$('#hidden_category_quantity_price_input').append(input_to_create);
	});
	$(document).on('click','.delete_category_quantity_pricing_from_list',function(e){
		e.preventDefault();
		let row_number = $(this).attr('id').substr(33);
		$('#category_quantity_price_infos_input_'+row_number).remove();
		$('#single_added_category_quantity_pricing_'+row_number).remove();
		rearrange_category_quantity_pricing_list();
	});

	$('#add_location_info_to_list').on('click', function(e){
		e.preventDefault();
		let location_quantity = $('#location_quantity').val();
		let location_price = $('#location_price').val();
		
		$('#location_quantity').val('');
		$('#location_price').val('');
		
		//last two is show only city and state, show the number
		let full_info_joined = `${location_quantity}|||${location_price}`;

		let location_quantity_price_list = $('#added_location_quantity_pricing_list').find('.single_added_location_quantity_pricing').length;
		let element_to_create = '';
		let input_to_create = '';
		if(location_quantity_price_list >0){
			element_to_create += `<li class="category-li single_added_location_quantity_pricing" id="single_added_location_quantity_pricing_${(location_quantity_price_list+1)}">
				<div class="display_none location_quantity_pricing_object">
					<span class="location_quantity">${location_quantity}</span>
					<span class="location_price">${location_price}</span>
				</div>
				<div>
					<span>${location_quantity}</span> locations | 
					<span>$${location_price}</span>
				</div>
				<br>
				<div class="delete_button_container"><button type="button" class="small-button primary white-text delete_location_quantity_pricing_from_list" id="delete_location_quantity_pricing_${(location_quantity_price_list+1)}">Delete</button></div>	
			</li>`;
			input_to_create += '<input type="hidden" value="'+full_info_joined+'" name="location_quantity_price_infos[]" id="location_quantity_price_infos_input_'+(location_quantity_price_list+1)+'">';
		}else{
			element_to_create += `<li class="category-li single_added_location_quantity_pricing" id="single_added_location_quantity_pricing_1">
				<div class="display_none location_quantity_pricing_object">
					<span class="location_quantity">${location_quantity}</span>
					<span class="location_price">${location_price}</span>
				</div>
				<div>
					<span>${location_quantity}</span> locations | 
					<span>$${location_price}</span>
				</div>
				<br>
				<div class="delete_button_container"><button type="button" class="small-button primary white-text delete_location_quantity_pricing_from_list" id="delete_location_quantity_pricing_1">Delete</button></div>	
			</li>`;
			input_to_create += '<input type="hidden" value="'+full_info_joined+'" name="location_quantity_price_infos[]" id="location_quantity_price_infos_input_1">';
		}
		$('#added_location_quantity_pricing_list').append(element_to_create);
		$('#hidden_location_quantity_price_input').append(input_to_create);
	});
	$(document).on('click','.delete_location_quantity_pricing_from_list',function(e){
		e.preventDefault();
		let row_number = $(this).attr('id').substr(33);
		$('#location_quantity_price_infos_input_'+row_number).remove();
		$('#single_added_location_quantity_pricing_'+row_number).remove();
		rearrange_location_quantity_pricing_list();
	});

	$('#add_banner_info_to_list').on('click', function(e){
		e.preventDefault();
		let banner_quantity = $('#banner_quantity').val();
		let banner_price = $('#banner_price').val();
		
		$('#banner_quantity').val('');
		$('#banner_price').val('');
		
		//last two is show only city and state, show the number
		let full_info_joined = `${banner_quantity}|||${banner_price}`;

		let banner_quantity_price_list = $('#added_banner_quantity_pricing_list').find('.single_added_banner_quantity_pricing').length;
		let element_to_create = '';
		let input_to_create = '';

		if(banner_quantity_price_list >0){
			element_to_create += `<li class="category-li single_added_banner_quantity_pricing" id="single_added_banner_quantity_pricing_${(banner_quantity_price_list+1)}">
				<div class="display_none banner_quantity_pricing_object">
					<span class="banner_quantity">${banner_quantity}</span>
					<span class="banner_price">${banner_price}</span>
				</div>
				<div>
					<span>${banner_quantity}</span> banners | 
					<span>$${banner_price}</span>
				</div>
				<br>
				<div class="delete_button_container"><button type="button" class="small-button primary white-text delete_banner_quantity_pricing_from_list" id="delete_banner_quantity_pricing_${(banner_quantity_price_list+1)}">Delete</button></div>	
			</li>`;
			input_to_create += '<input type="hidden" value="'+full_info_joined+'" name="banner_quantity_price_infos[]" id="banner_quantity_price_infos_input_'+(banner_quantity_price_list+1)+'">';
		}else{
			element_to_create += `<li class="category-li single_added_banner_quantity_pricing" id="single_added_banner_quantity_pricing_1">
				<div class="display_none banner_quantity_pricing_object">
					<span class="banner_quantity">${banner_quantity}</span>
					<span class="banner_price">${banner_price}</span>
				</div>
				<div>
					<span>${banner_quantity}</span> banners | 
					<span>$${banner_price}</span>
				</div>
				<br>
				<div class="delete_button_container"><button type="button" class="small-button primary white-text delete_banner_quantity_pricing_from_list" id="delete_banner_quantity_pricing_1">Delete</button></div>	
			</li>`;
			input_to_create += '<input type="hidden" value="'+full_info_joined+'" name="banner_quantity_price_infos[]" id="banner_quantity_price_infos_input_1">';
		}
		$('#added_banner_quantity_pricing_list').append(element_to_create);
		$('#hidden_banner_quantity_price_input').append(input_to_create);
	});
	$(document).on('click','.delete_banner_quantity_pricing_from_list',function(e){
		e.preventDefault();
		let row_number = $(this).attr('id').substr(31);
		$('#banner_quantity_price_infos_input_'+row_number).remove();
		$('#single_added_banner_quantity_pricing_'+row_number).remove();
		rearrange_banner_quantity_pricing_list();
	});
});

function rearrange_keyword_quantity_pricing_list(){
	let locations_in_list = $('#added_keyword_quantity_pricing_list').find('.single_added_keyword_quantity_pricing').length;
	$('.single_added_keyword_quantity_pricing').each(function(index, single_object) {
		if(index == 0){
			$(this).attr('id','single_added_keyword_quantity_pricing_1');
			$(this).find('.delete_button_container').children(":first").attr('id','delete_keyword_quantity_pricing_1');
		}else{
			$(this).attr('id','single_added_keyword_quantity_pricing_'+(index+1));
			$(this).find('.delete_button_container').children(":first").attr('id','delete_keyword_quantity_pricing_'+(index+1));
		}
	});
	$('#hidden_keyword_quantity_price_input input').each(function(index, single_object) {
		if(index == 0){
			$(this).attr('id','keyword_quantity_price_infos_input_1');
		}else{
			$(this).attr('id','keyword_quantity_price_infos_input_'+(index+1));
		}
	});
}
function rearrange_category_quantity_pricing_list(){
	let locations_in_list = $('#added_category_quantity_pricing_list').find('.single_added_category_quantity_pricing').length;
	$('.single_added_category_quantity_pricing').each(function(index, single_object) {
		if(index == 0){
			$(this).attr('id','single_added_category_quantity_pricing_1');
			$(this).find('.delete_button_container').children(":first").attr('id','delete_category_quantity_pricing_1');
		}else{
			$(this).attr('id','single_added_category_quantity_pricing_'+(index+1));
			$(this).find('.delete_button_container').children(":first").attr('id','delete_category_quantity_pricing_'+(index+1));
		}
	});
	$('#hidden_category_quantity_price_input input').each(function(index, single_object) {
		if(index == 0){
			$(this).attr('id','category_quantity_price_infos_input_1');
		}else{
			$(this).attr('id','category_quantity_price_infos_input_'+(index+1));
		}
	});
}
function rearrange_location_quantity_pricing_list(){
	let locations_in_list = $('#added_location_quantity_pricing_list').find('.single_added_location_quantity_pricing').length;
	$('.single_added_location_quantity_pricing').each(function(index, single_object) {
		if(index == 0){
			$(this).attr('id','single_added_location_quantity_pricing_1');
			$(this).find('.delete_button_container').children(":first").attr('id','delete_location_quantity_pricing_1');
		}else{
			$(this).attr('id','single_added_location_quantity_pricing_'+(index+1));
			$(this).find('.delete_button_container').children(":first").attr('id','delete_location_quantity_pricing_'+(index+1));
		}
	});
	$('#hidden_location_quantity_price_input input').each(function(index, single_object) {
		if(index == 0){
			$(this).attr('id','location_quantity_price_infos_input_1');
		}else{
			$(this).attr('id','location_quantity_price_infos_input_'+(index+1));
		}
	});
}
function rearrange_banner_quantity_pricing_list(){
	let banners_in_list = $('#added_banner_quantity_pricing_list').find('.single_added_banner_quantity_pricing').length;
	$('.single_added_banner_quantity_pricing').each(function(index, single_object) {
		if(index == 0){
			$(this).attr('id','single_added_banner_quantity_pricing_1');
			$(this).find('.delete_button_container').children(":first").attr('id','delete_banner_quantity_pricing_1');
		}else{
			$(this).attr('id','single_added_banner_quantity_pricing_'+(index+1));
			$(this).find('.delete_button_container').children(":first").attr('id','delete_banner_quantity_pricing_'+(index+1));
		}
	});
	$('#hidden_banner_quantity_price_input input').each(function(index, single_object) {
		if(index == 0){
			$(this).attr('id','banner_quantity_price_infos_input_1');
		}else{
			$(this).attr('id','banner_quantity_price_infos_input_'+(index+1));
		}
	});
}