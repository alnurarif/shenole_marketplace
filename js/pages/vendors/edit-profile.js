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
	$('#keyword-vendor-search').on('keyup',function(){
		let full_keywords = $(this).val();
		let keywords_array = full_keywords.trim().replace(/^,|,$/g, '').split(',');
		let keyword_row = '';
		if(keywords_array[0] != "" && keywords_array.length > 0){
			keywords_array.forEach(function(single_word, index){
				keyword_row += `<li class="category-li" id="keyword_${index+1}">
					<div>${single_word}</div>
					<br>
					<div>
						<button type="button" class="small-button primary white-text keyword_delete" id="keyword_delete_${index+1}">Delete</button>
					</div>
				</li>`;
				
			});
		};
		$('#keyword_list').html(keyword_row);
	})
	$(document).on('click','.keyword_delete',function(e){
		e.preventDefault();
		let keyword_index = $(this).attr('id').substr(15);
		let full_keywords = $('#keyword-vendor-search').val();
		let keywords_array = full_keywords.trim().replace(/^,|,$/g, '').split(',');
		let full_string = '';
		keywords_array.forEach(function(single_word, index){
			if((index+1) != keyword_index){
				full_string += single_word+',';
			}
		});
		
		$('#keyword-vendor-search').val(full_string.trim().replace(/^,|,$/g, ''));
		
		let new_keywords_array = full_string.trim().replace(/^,|,$/g, '').split(',');
		let keyword_row = '';
		if(new_keywords_array[0] != "" && new_keywords_array.length > 0){

			new_keywords_array.forEach(function(single_word, index){
				keyword_row += `<li class="category-li" id="keyword_${index+1}">
					<div>${single_word}</div>
					<br>
					<div>
						<button type="button" class="small-button primary white-text keyword_delete" id="keyword_delete_${index+1}">Delete</button>
					</div>
				</li>`;
				
			});
		}
		$('#keyword_list').html(keyword_row);
	});
	$('#description_textarea').on('keyup',function(){
		let textarea_value = $(this).val();
		setDescription(textarea_value);
	});
	$(document).on('click','.delete_category_from_list',function(e){
		e.preventDefault();
		let row_number = $(this).attr('id').substr(23);
		$('#vendor_category_input_'+row_number).remove();
		$('#single_vendor_category_'+row_number).remove();
		rearrange_category_list();
	});
	$(document).on('click','.delete_location_from_list',function(e){
		e.preventDefault();
		let row_number = $(this).attr('id').substr(23);
		$('#vendor_location_input_'+row_number).remove();
		$('#single_vendor_location_'+row_number).remove();
		rearrange_location_list();
	});
	$(document).on('click','.make_primary_from_list', function(e){
		let row_number = $(this).attr('id').substr(22);
		let primary_index = parseInt(row_number) - 1;
		let added_category_inputs = [];
		let added_category_list = [];
		$('.single_added_category').each(function(index, single_object) {
			let category_name = $(this).find('div:first').html();
			added_category_list.push(category_name);
		});
		$('#hidden_category_input input').each(function(index, single_object) {
			let category_id = $(this).val();
			added_category_inputs.push(category_id);
		});
		recreate_to_update_primary(primary_index, added_category_list, added_category_inputs);


	});
	$(document).on('click','.make_primary_from_location_list', function(e){
		let row_number = $(this).attr('id').substr(22);
		let primary_index = parseInt(row_number) - 1;
		let added_location_inputs = [];
		let added_location_list = [];
		$('.single_added_location').each(function(index, single_object) {
			let location_object = {
				location_street_address_1 : $(this).find('div:first').find('.location_object').find('.location_street_address_1').html(),
				location_street_address_2 : $(this).find('div:first').find('.location_object').find('.location_street_address_2').html(),
				location_city : $(this).find('div:first').find('.location_object').find('.location_city').html(),
				location_state_id : $(this).find('div:first').find('.location_object').find('.location_state_id').html(),
				location_state_name : $(this).find('div:first').find('.location_object').find('.location_state_name').html(),
				location_zip : $(this).find('div:first').find('.location_object').find('.location_zip').html(),
				location_phone_number : $(this).find('div:first').find('.location_object').find('.location_phone_number').html()
			};
			
			added_location_list.push(location_object);
		});
		$('#hidden_location_input input').each(function(index, single_object) {
			let location_values = $(this).val();
			added_location_inputs.push(location_values);
		});
		recreate_to_update_location_primary(primary_index, added_location_list, added_location_inputs);


	});
	$('#add_category_button').on('click',function(e){
		e.preventDefault();
		let categories_in_list = $('#added_category_list').find('.single_added_category').length;
		let added_category_name = $('#category').find('option:selected').text();
		let added_category_id = $('#category').val();
		let element_to_create = '';
		let input_to_create = '';

		if(categories_in_list >0){
			element_to_create += '<li class="category-li single_added_category" id="single_vendor_category_'+(categories_in_list+1)+'">';
				element_to_create += '<div>'+added_category_name+'</div>';
				element_to_create += '<br>';
				element_to_create += '<div class="multi-button-container">';
					element_to_create += '<button type="button" class="small-button primary white-text make_primary_from_list" id="make_primary_category_'+(categories_in_list+1)+'">Make Primary</button>';
					element_to_create += '<button type="button" class="small-button primary white-text delete_category_from_list" id="delete_vendor_category_'+(categories_in_list+1)+'">Delete</button>';
				element_to_create += '</div>';
			element_to_create +='</li>';
			input_to_create += '<input type="hidden" value="'+added_category_id+'" name="vendor_categories[]" id="vendor_category_input_'+(categories_in_list+1)+'">';
		}else{
			element_to_create += '<li class="category-li single_added_category" id="single_vendor_category_1">';
				element_to_create += '<div class="primary-selection">'+added_category_name+'</div>';
				element_to_create += '<br>';
				element_to_create += '<div class="multi-button-container">';
					element_to_create += '<button type="button" class="small-button primary white-text delete_category_from_list" id="delete_vendor_category_1">Delete</button>';
				element_to_create += '</div>';
			element_to_create += '</li>';
			input_to_create += '<input type="hidden" value="'+added_category_id+'" name="vendor_categories[]" id="vendor_category_input_1">';
		}
		$('#added_category_list').append(element_to_create);
		$('#hidden_category_input').append(input_to_create);
	});
	$('#add_location_to_list').on('click', function(e){
		e.preventDefault();
		let location_street_address_1 = $('#location_street_address_1').val();
		let location_street_address_2 = $('#location_street_address_2').val();
		let location_city = $('#location_city').val();
		let location_state_id = $('#location_state').val();
		let location_state_name = $('#location_state').find('option:selected').text();
		let location_zip = $('#location_zip').val();
		let location_phone_number = $('#location_phone_number').val();
		//last two is show only city and state, show the number
		let full_location_joined = `${location_street_address_1}|||${location_street_address_2}|||${location_city}|||${location_state_id}|||${location_zip}|||${location_phone_number}|||0|||0`;


		let locations_in_list = $('#added_location_list').find('.single_added_location').length;
		let element_to_create = '';
		let input_to_create = '';

		if(locations_in_list >0){
			element_to_create += `<li class="category-li single_added_location" id="single_vendor_location_${(locations_in_list+1)}">
				<div class="aux-location">
					<div class="display_none location_object">
						<span class="location_street_address_1">${location_street_address_1}</span>
						<span class="location_street_address_2">${location_street_address_2}</span>
						<span class="location_city">${location_city}</span>
						<span class="location_state_id">${location_state_id}</span>
						<span class="location_state_name">${location_state_name}</span>
						<span class="location_zip">${location_zip}</span>
						<span class="location_phone_number">${location_phone_number}</span>
					</div>
					<span class="location-list-text street_address_1">${location_street_address_1}</span><br>
					<span class="location-list-text street_address_2">${location_street_address_2}</span><br>
					<span class="location-list-text city_state_name">${location_city}, ${location_state_name}</span><br>
					<span class="location-list-text zip">${location_zip}</span><br>
					<div class="spacer-10px"></div>
					<div class="spacer-10px"></div>
					<span class="location-list-text-phone phone_number display_none">${location_phone_number}</span>
				</div>
				<br>
				<div>
					<label for="only-city-01" class="input-label">Only Show The City And State
					</label>
					<div class="spacer-10px"></div>
					<input type="checkbox" name="only-city-01" class="show_city_and_state" id="only_show_city_and_state_${(locations_in_list+1)}">
					<div class="spacer-10px"></div>
					<div class="spacer-10px"></div>
				</div>
				<div>
					<label for="show-phone-01" class="input-label">Show The Phone Number</label>
					<div class="spacer-10px"></div>
					<input type="checkbox" name="only-city-01" class="show_the_phone_number" id="show_the_phone_number_${(locations_in_list+1)}">
					<div class="spacer-10px"></div>
					<div class="spacer-10px"></div>
				</div>
				<div class="multi-button-container">
					<button type="button" class="small-button primary white-text make_primary_from_location_list" id="make_primary_location_${(locations_in_list+1)}">Make Primary</button>
					<button type="button" class="small-button primary white-text delete_location_from_list" id="delete_vendor_location_${(locations_in_list+1)}">Delete</button>
				</div>
			</li>`;
			input_to_create += '<input type="hidden" value="'+full_location_joined+'" name="vendor_locations[]" id="vendor_location_input_'+(locations_in_list+1)+'">';
		}else{
			element_to_create += `<li class="category-li single_added_location" id="single_vendor_location_1">
				<div class="primary-location">
					<div class="display_none location_object">
						<span class="location_street_address_1">${location_street_address_1}</span>
						<span class="location_street_address_2">${location_street_address_2}</span>
						<span class="location_city">${location_city}</span>
						<span class="location_state_id">${location_state_id}</span>
						<span class="location_state_name">${location_state_name}</span>
						<span class="location_zip">${location_zip}</span>
						<span class="location_phone_number">${location_phone_number}</span>
					</div>
					<span class="location-list-text street_address_1">${location_street_address_1}</span><br>
					<span class="location-list-text street_address_2">${location_street_address_2}</span><br>
					<span class="location-list-text city_state_name">${location_city}, ${location_state_name}</span><br>
					<span class="location-list-text zip">${location_zip}</span><br>
					<div class="spacer-10px"></div>
					<div class="spacer-10px"></div>
					<span class="location-list-text-phone phone_number display_none">${location_phone_number}</span>
				</div>
				<br>
				<div>
					<label for="only-city-01" class="input-label">Only Show The City And State
					</label>
					<div class="spacer-10px"></div>
					<input type="checkbox" name="only-city-01" class="show_city_and_state" id="only_show_city_and_state_1">
					<div class="spacer-10px"></div>
					<div class="spacer-10px"></div>
				</div>
				<div>
					<label for="show-phone-01" class="input-label">Show The Phone Number</label>
					<div class="spacer-10px"></div>
					<input type="checkbox" name="only-city-01" class="show_the_phone_number" id="show_the_phone_number_1">
					<div class="spacer-10px"></div>
					<div class="spacer-10px"></div>
				</div>
				<div class="multi-button-container">
					<button type="button" class="small-button primary white-text delete_location_from_list" id="delete_vendor_location_1">Delete</button>
				</div>
			</li>`;
			
			input_to_create += '<input type="hidden" value="'+full_location_joined+'" name="vendor_locations[]" id="vendor_location_input_1">';
		}
		$('#added_location_list').append(element_to_create);
		$('#hidden_location_input').append(input_to_create);
	});
	$(document).on('click', '.show_city_and_state', function(e){
		let this_id = $(this).attr('id').substr(25);
		let location_all_unarranged_value = $('#vendor_location_input_'+this_id).val();
		let all_values_array = location_all_unarranged_value.split("|||");
		
		if($(this).is(":checked")){
			let updated_value = `${all_values_array[0]}|||${all_values_array[1]}|||${all_values_array[2]}|||${all_values_array[3]}|||${all_values_array[4]}|||${all_values_array[5]}|||1|||${all_values_array[7]}`;
			$('#single_vendor_location_'+this_id).find('div:first').find('.street_address_1').addClass('display_none');
			$('#single_vendor_location_'+this_id).find('div:first').find('.street_address_2').addClass('display_none');
			$('#single_vendor_location_'+this_id).find('div:first').find('.zip').addClass('display_none');
			$('#vendor_location_input_'+this_id).val(updated_value);
		}else{
			let updated_value = `${all_values_array[0]}|||${all_values_array[1]}|||${all_values_array[2]}|||${all_values_array[3]}|||${all_values_array[4]}|||${all_values_array[5]}|||0|||${all_values_array[7]}`;
			$('#single_vendor_location_'+this_id).find('div:first').find('.street_address_1').removeClass('display_none');
			$('#single_vendor_location_'+this_id).find('div:first').find('.street_address_2').removeClass('display_none');
			$('#single_vendor_location_'+this_id).find('div:first').find('.zip').removeClass('display_none');
			$('#vendor_location_input_'+this_id).val(updated_value);
		}
		
	});
	$(document).on('click', '.show_the_phone_number', function(e){
		let this_id = $(this).attr('id').substr(22);
		let location_all_unarranged_value = $('#vendor_location_input_'+this_id).val();
		let all_values_array = location_all_unarranged_value.split("|||");
		
		if($(this).is(":checked")){
			let updated_value = `${all_values_array[0]}|||${all_values_array[1]}|||${all_values_array[2]}|||${all_values_array[3]}|||${all_values_array[4]}|||${all_values_array[5]}|||${all_values_array[6]}|||1`;
			$('#single_vendor_location_'+this_id).find('div:first').find('.phone_number').removeClass('display_none');
			$('#vendor_location_input_'+this_id).val(updated_value);
		}else{
			let updated_value = `${all_values_array[0]}|||${all_values_array[1]}|||${all_values_array[2]}|||${all_values_array[3]}|||${all_values_array[4]}|||${all_values_array[5]}|||${all_values_array[6]}|||0`;
			$('#single_vendor_location_'+this_id).find('div:first').find('.phone_number').addClass('display_none');
			$('#vendor_location_input_'+this_id).val(updated_value);
		}
		
	});
});
function recreate_to_update_primary(primary_index, added_category_list, added_category_inputs){
	let first_element_to_show = '';
	let first_input_to_add = '';
	let element_to_create = '';
	let input_to_create = '';
	let all_elements_to_create = '';
	let all_inputs_to_create = '';
	element_to_create += '<li class="category-li single_added_category" id="single_vendor_category_1">';
		element_to_create += '<div class="primary-selection">'+added_category_list[primary_index]+'</div>';
		element_to_create += '<br>';
		element_to_create += '<div class="multi-button-container">';
			element_to_create += '<button type="button" class="small-button primary white-text delete_category_from_list" id="delete_vendor_category_1">Delete</button>';
		element_to_create += '</div>';
	element_to_create += '</li>';
	input_to_create += '<input type="hidden" value="'+added_category_inputs[primary_index]+'" name="vendor_categories[]" id="vendor_category_input_1">';

	$('#added_category_list').html(element_to_create);
	$('#hidden_category_input').html(input_to_create);

	let i = 2;
	added_category_list.forEach(function(element, index){
		if(primary_index != index){
			all_elements_to_create += '<li class="category-li single_added_category" id="single_vendor_category_'+i+'">';
				all_elements_to_create += '<div>'+element+'</div>';
				all_elements_to_create += '<br>';
				all_elements_to_create += '<div class="multi-button-container">';
					all_elements_to_create += '<button type="button" class="small-button primary white-text make_primary_from_list" id="make_primary_category_'+i+'">Make Primary</button>';
					all_elements_to_create += '<button type="button" class="small-button primary white-text delete_category_from_list" id="delete_vendor_category_'+i+'">Delete</button>';
				all_elements_to_create += '</div>';
			all_elements_to_create +='</li>';
			i++;
		}
	});
	$('#added_category_list').append(all_elements_to_create);

	i = 2;
	added_category_inputs.forEach(function(element, index){
		if(primary_index != index){
			all_inputs_to_create += '<input type="hidden" value="'+element+'" name="vendor_categories[]" id="vendor_category_input_'+i+'">';
			i++;
		}
	});
	$('#hidden_category_input').append(all_inputs_to_create);
	

}
function recreate_to_update_location_primary(primary_index, added_location_list, added_location_inputs){
	let first_element_to_show = '';
	let first_input_to_add = '';
	let element_to_create = '';
	let input_to_create = '';
	let all_elements_to_create = '';
	let all_inputs_to_create = '';
	
	input_to_create += '<input type="hidden" value="'+added_location_inputs[primary_index]+'" name="vendor_locations[]" id="vendor_location_input_1">';
	$('#hidden_location_input').html(input_to_create);
	let location_all_unarranged_value = $('#vendor_location_input_1').val();
	let all_values_array = location_all_unarranged_value.split("|||");
	let show_only_city_state = (all_values_array[6] == 1) ? 'display_none' : '';
	let show_mobile_number = (all_values_array[7] == 0) ? 'display_none' : '';
	let checked_only_city_state = (all_values_array[6] == 1) ? 'checked' : '';
	let checked_mobile_number = (all_values_array[7] == 1) ? 'checked' : '';
	element_to_create += `<li class="category-li single_added_location" id="single_vendor_location_1">
				<div class="primary-location">
					<div class="display_none location_object">
						<span class="location_street_address_1">${added_location_list[primary_index].location_street_address_1}</span>
						<span class="location_street_address_2">${added_location_list[primary_index].location_street_address_2}</span>
						<span class="location_city">${added_location_list[primary_index].location_city}</span>
						<span class="location_state_id">${added_location_list[primary_index].location_state_id}</span>
						<span class="location_state_name">${added_location_list[primary_index].location_state_name}</span>
						<span class="location_zip">${added_location_list[primary_index].location_zip}</span>
						<span class="location_phone_number">${added_location_list[primary_index].location_phone_number}</span>
					</div>
					<span class="location-list-text street_address_1 ${show_only_city_state}">${added_location_list[primary_index].location_street_address_1}</span><br>
					<span class="location-list-text street_address_2 ${show_only_city_state}">${added_location_list[primary_index].location_street_address_2}</span><br>
					<span class="location-list-text city_state_name">${added_location_list[primary_index].location_city}, ${added_location_list[primary_index].location_state_name}</span><br>
					<span class="location-list-text zip ${show_only_city_state}">${added_location_list[primary_index].location_zip}</span><br>
					<div class="spacer-10px"></div>
					<div class="spacer-10px"></div>
					<span class="location-list-text-phone phone_number ${show_mobile_number}">${added_location_list[primary_index].location_phone_number}</span>
				</div>
				<br>
				<div>
					<label for="only-city-01" class="input-label">Only Show The City And State
					</label>
					<div class="spacer-10px"></div>
					<input type="checkbox" name="only-city-01" class="show_city_and_state" id="only_show_city_and_state_1" ${checked_only_city_state}>
					<div class="spacer-10px"></div>
					<div class="spacer-10px"></div>
				</div>
				<div>
					<label for="show-phone-01" class="input-label">Show The Phone Number</label>
					<div class="spacer-10px"></div>
					<input type="checkbox" name="only-city-01" class="show_the_phone_number" id="show_the_phone_number_1" ${checked_mobile_number}>
					<div class="spacer-10px"></div>
					<div class="spacer-10px"></div>
				</div>
				<div class="multi-button-container">
					<button type="button" class="small-button primary white-text delete_location_from_list" id="delete_vendor_location_1">Delete</button>
				</div>
			</li>`;

	

	$('#added_location_list').html(element_to_create);
	
	let i = 2;
	
	added_location_inputs.forEach(function(element, index){
		if(primary_index != index){
			all_inputs_to_create += '<input type="hidden" value="'+element+'" name="vendor_locations[]" id="vendor_location_input_'+i+'">';
			i++;
		}
	});
	$('#hidden_location_input').append(all_inputs_to_create);

	
	i = 2;

	added_location_list.forEach(function(element, index){
		
		if(primary_index != index){
			let unarranged_value = $('#vendor_location_input_'+i).val();
			let values_array = unarranged_value.split("|||");
			let show_only_citystate = (values_array[6] == 1) ? 'display_none' : '';
			let show_mobilenumber = (values_array[7] == 0) ? 'display_none' : '';
			let checked_only_citystate = (values_array[6] == 1) ? 'checked' : '';
			let checked_mobilenumber = (values_array[7] == 1) ? 'checked' : '';
			all_elements_to_create += `<li class="category-li single_added_location" id="single_vendor_location_${i}">
				<div class="aux-location">
					<div class="display_none location_object">
						<span class="location_street_address_1">${element.location_street_address_1}</span>
						<span class="location_street_address_2">${element.location_street_address_2}</span>
						<span class="location_city">${element.location_city}</span>
						<span class="location_state_id">${element.location_state_id}</span>
						<span class="location_state_name">${element.location_state_name}</span>
						<span class="location_zip">${element.location_zip}</span>
						<span class="location_phone_number">${element.location_phone_number}</span>
					</div>
					<span class="location-list-text street_address_1 ${show_only_citystate}">${element.location_street_address_1}</span><br>
					<span class="location-list-text street_address_2 ${show_only_citystate}">${element.location_street_address_2}</span><br>
					<span class="location-list-text city_state_name">${element.location_city}, ${element.location_state_name}</span><br>
					<span class="location-list-text zip ${show_only_citystate}">${element.location_zip}</span><br>
					<div class="spacer-10px"></div>
					<div class="spacer-10px"></div>
					<span class="location-list-text-phone phone_number ${show_mobilenumber}">${element.location_phone_number}</span>
				</div>
				<br>
				<div>
					<label for="only-city-01" class="input-label">Only Show The City And State
					</label>
					<div class="spacer-10px"></div>
					<input type="checkbox" name="only-city-01" class="show_city_and_state" id="only_show_city_and_state_${i}" ${checked_only_citystate}>
					<div class="spacer-10px"></div>
					<div class="spacer-10px"></div>
				</div>
				<div>
					<label for="show-phone-01" class="input-label">Show The Phone Number</label>
					<div class="spacer-10px"></div>
					<input type="checkbox" name="only-city-01" class="show_the_phone_number" id="show_the_phone_number_${i}" ${checked_mobilenumber}>
					<div class="spacer-10px"></div>
					<div class="spacer-10px"></div>
				</div>
				<div class="multi-button-container">
					<button type="button" class="small-button primary white-text make_primary_from_location_list" id="make_primary_location_${i}">Make Primary</button>
					<button type="button" class="small-button primary white-text delete_location_from_list" id="delete_vendor_location_${i}">Delete</button>
				</div>
			</li>`;
			i++;
		}
	});
	$('#added_location_list').append(all_elements_to_create);

	
	

}
function rearrange_category_list(){
	let categories_in_list = $('#added_category_list').find('.single_added_category').length;
	$('.single_added_category').each(function(index, single_object) {
		if(index == 0){
			$(this).attr('id','single_vendor_category_1');
			$(this).find('div:first').addClass('primary-selection');
			$(this).find('.multi-button-container').find('.make_primary_from_list').remove();
			$(this).find('.multi-button-container').find('.delete_category_from_list').attr('id','delete_vendor_category_1');
		}else{
			$(this).attr('id','single_vendor_category_'+(index+1));
			$(this).find('.multi-button-container').find('.delete_category_from_list').attr('id','delete_vendor_category_'+(index+1));
			$(this).find('.multi-button-container').find('.make_primary_from_list').attr('id','make_primary_category_'+(index+1));
		}
	});
	$('#hidden_category_input input').each(function(index, single_object) {
		if(index == 0){
			$(this).attr('id','vendor_category_input_1');
		}else{
			$(this).attr('id','vendor_category_input_'+(index+1));
		}
	});
}

function rearrange_location_list(){
	let locations_in_list = $('#added_location_list').find('.single_added_location').length;
	$('.single_added_location').each(function(index, single_object) {
		if(index == 0){
			$(this).attr('id','single_vendor_location_1');
			$(this).find('div:first').addClass('primary-location').removeClass('aux-location');
			$(this).find('.multi-button-container').find('.make_primary_from_location_list').remove();
			$(this).find('.multi-button-container').find('.delete_location_from_list').attr('id','delete_vendor_location_1');
			$(this).find('.show_city_and_state').attr('id','only_show_city_and_state_1');
			$(this).find('.show_the_phone_number').attr('id','show_the_phone_number_1');
		}else{
			$(this).attr('id','single_vendor_location_'+(index+1));
			$(this).find('div:first').addClass('aux-location').removeClass('primary-location');
			$(this).find('.multi-button-container').find('.delete_location_from_list').attr('id','delete_vendor_location_'+(index+1));
			$(this).find('.multi-button-container').find('.make_primary_from_location_list').attr('id','make_primary_location_'+(index+1));
			$(this).find('.show_city_and_state').attr('id','only_show_city_and_state_'+(index+1));
			$(this).find('.show_the_phone_number').attr('id','show_the_phone_number_'+(index+1));
		}
	});
	$('#hidden_location_input input').each(function(index, single_object) {
		if(index == 0){
			$(this).attr('id','vendor_location_input_1');
		}else{
			$(this).attr('id','vendor_location_input_'+(index+1));
		}
	});
}
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