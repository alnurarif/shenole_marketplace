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
	$('.banner_position_price').on('change',function(){
		$('.banner_position_price').prop('checked',false);
		if($(this).prop('checked') == false){
			$(this).prop('checked',true);
			set_ad_campaign_total();
		}else{
			$(this).prop('checked',false);
		}
	});
	$('#campaign_length_select').on('change',function(){
		set_ad_campaign_total();
	});
	$('#campaign_keyword_prices_select').on('change',function(){
		set_ad_campaign_total();
	});
	$('#category_prices_select').on('change',function(){
		set_ad_campaign_total();
	});
	$('#location_prices_select').on('change',function(){
		set_ad_campaign_total();
	});
	$('#banner_prices_select').on('change',function(){
		set_ad_campaign_total();
	});
	$('#comma_separated_keywords').on('keyup',function(){
		var comma_separated_keywords_value_string = $(this).val();
		var comma_separated_keywords_value_array = comma_separated_keywords_value_string.split(',');
		var filtered_comma_separated_keywords_value_array = comma_separated_keywords_value_array.filter(item => item);

		var number_of_keywords_written = filtered_comma_separated_keywords_value_array.length;
		
	});
	$(document).on('click', '.single_keyword_delete', function(e){
		e.preventDefault();
		$(this).parent().parent().remove();
		$('#campaign_keyword_prices_select').attr('disabled',false);
	});
	$(document).on('click', '.single_category_delete', function(e){
		e.preventDefault();
		$(this).parent().parent().remove();
		$('#category_prices_select').attr('disabled',false);
	});
	$(document).on('click', '.single_location_delete', function(e){
		e.preventDefault();
		$(this).parent().parent().remove();
		$('#location_prices_select').attr('disabled',false);
	});
	$('#continue_shopping').on('click',function(){
		$('.shopping-cart-modal').fadeOut();
	});
	$('#cancel_shopping').click(function() {
		location.reload();
	});
	$('#add_keywords').on('click',function(){
		var comma_separated_keywords_value_string = $('#comma_separated_keywords').val();
		var comma_separated_keywords_value_array = comma_separated_keywords_value_string.split(',');
		var filtered_comma_separated_keywords_value_array = comma_separated_keywords_value_array.filter(item => item);

		var number_of_keywords_written = filtered_comma_separated_keywords_value_array.length;

		var number_of_keywords_in_the_box = $('.single_keyword').length;

		var total_keywords_willing_to_add = parseInt(number_of_keywords_written) + parseInt(number_of_keywords_in_the_box);

		var total_keywords_are_permitted = parseInt($('#max_keyword_number_to_show').text());
		
		if(total_keywords_are_permitted == 0){
			$('#keyword_error_message').text('No keyword number is selected');
			$('#keyword_error_message').show();
			return false;
		}
		if(number_of_keywords_written == 0){
			$('#keyword_error_message').text('No keyword is written');
			$('#keyword_error_message').show();
			return false;
		}
		if(total_keywords_are_permitted < total_keywords_willing_to_add){
			$('#keyword_error_message').text('You exceeded the keywords limit!');
			$('#keyword_error_message').show();
		}else{
			$('#keyword_error_message').hide();
			var keyword_list_to_show = '';
			filtered_comma_separated_keywords_value_array.forEach(function(keyword, index){
				keyword_list_to_show += '<li class="category-li single_keyword"><div class="keyword_name">'+keyword+'</div><br><div><button type="button" class="small-button primary white-text single_keyword_delete">Delete</button></div></li>';
			});
			$('#keywords_list_box').append(keyword_list_to_show);
			$('#comma_separated_keywords').val('');
			$('#campaign_keyword_prices_select').attr('disabled',true);
		}
	});

	$(document).on('click', '#add_category',function(){
		
		var category_value_string = $('#category_name').val();
		var category_number_selected = $('#category_prices_select').val();
		if(category_number_selected == ""){
			$('#category_error_message').text('No category number is selected');
			$('#category_error_message').show();
			return false;
		}
		if(category_value_string == ""){
			$('#category_error_message').text('No category is selected');
			$('#category_error_message').show();
			return false;
		}
		var already_exist = false;
		$('.single_category').each(function(){
			if(category_value_string == $(this).find('.category_name').text()){
				$('#category_error_message').text("It's already selected");
				$('#category_error_message').show();
				already_exist = true;
			}
		});

		if(already_exist) return false; 

		var number_of_categories_in_the_box = $('.single_category').length;

		var total_categories_willing_to_add = 1 + parseInt(number_of_categories_in_the_box);

		var total_categories_are_permitted = parseInt(category_number_selected);
		
		if(total_categories_are_permitted < total_categories_willing_to_add){
			$('#category_error_message').text('You exceeded the categories limit!');
			$('#category_error_message').show();
			return false;
		}else{
			$('#category_error_message').hide();
			var category_list_to_show = '';
			category_list_to_show += '<li class="category-li single_category"><div class="category_name">'+category_value_string+'</div><br><div><button type="button" class="small-button primary white-text single_category_delete">Delete</button></div></li>';
			$('#categories_list_box').append(category_list_to_show);
			$('#category_name').val('');
			$('#category_prices_select').attr('disabled',true);
		}
	});


	$(document).on('click', '#add_location',function(){
		
		var location_value_string = $('#location_name').val();
		var location_number_selected = $('#location_prices_select').val();
		if(location_number_selected == ""){
			$('#location_error_message').text('No location number is selected');
			$('#location_error_message').show();
			return false;
		}
		if(location_value_string == ""){
			$('#location_error_message').text('No location is selected');
			$('#location_error_message').show();
			return false;
		}
		var already_exist = false;
		$('.single_location').each(function(){
			if(location_value_string == $(this).find('.location_name').text()){
				$('#location_error_message').text("It's already in the list!");
				$('#location_error_message').show();
				already_exist = true;
			}
		});

		if(already_exist) return false; 

		var number_of_locations_in_the_box = $('.single_location').length;

		var total_locations_willing_to_add = 1 + parseInt(number_of_locations_in_the_box);

		var total_locations_are_permitted = parseInt(location_number_selected);
		if(total_locations_are_permitted < total_locations_willing_to_add){
			$('#location_error_message').text('You exceeded the locations limit!');
			$('#location_error_message').show();
			return false;
		}else{
			$('#location_error_message').hide();
			var location_list_to_show = '';
			location_list_to_show += '<li class="category-li single_location"><div class="location_name">'+location_value_string+'</div><br><div><button type="button" class="small-button primary white-text single_location_delete">Delete</button></div></li>';
			$('#locations_list_box').append(location_list_to_show);
			$('#location_name').val('');
			$('#location_prices_select').attr('disabled',true);
		}
	});
	$('.close_modal_cross').on('click',function(){
		$(this).parent().parent().fadeOut();
	});
	$('#submit_ad').on('click',function(){
		var base_url = $('base').attr('href');
		var ad_campaign_length_mixed_string = $('#campaign_length_select').val();
		var ad_campaign_length_mixed_string_splitted = ad_campaign_length_mixed_string.split('||');
		var ad_campaign_period = (ad_campaign_length_mixed_string_splitted[0] == "") ? "" : ad_campaign_length_mixed_string_splitted[0];
		var ad_campaign_price = (typeof ad_campaign_length_mixed_string_splitted[1] === "undefined") ? 0 : parseFloat(ad_campaign_length_mixed_string_splitted[1]);

		var campaign_keyword_setting_mixed_string = $('#campaign_keyword_prices_select').val();
		var campaign_keyword_setting_mixed_string_splitted = campaign_keyword_setting_mixed_string.split('||');
		var campaign_keyword_length = (campaign_keyword_setting_mixed_string_splitted[0] == "") ? 0 : campaign_keyword_setting_mixed_string_splitted[0];
		var campaing_keyword_price = (typeof campaign_keyword_setting_mixed_string_splitted[1] === "undefined") ? 0 : parseFloat(campaign_keyword_setting_mixed_string_splitted[1]);

		var campaign_category_setting_mixed_string = $('#category_prices_select').val();
		var campaign_category_setting_mixed_string_splitted = campaign_category_setting_mixed_string.split('||');
		var campaign_category_quantity = (campaign_category_setting_mixed_string_splitted[0] == "") ? 0 : campaign_category_setting_mixed_string_splitted[0];
		var campaing_category_price = (typeof campaign_category_setting_mixed_string_splitted[1] === "undefined") ? 0 : parseFloat(campaign_category_setting_mixed_string_splitted[1]);

		var campaign_location_setting_mixed_string = $('#location_prices_select').val();
		var campaign_location_setting_mixed_string_splitted = campaign_location_setting_mixed_string.split('||');
		var campaign_location_quantity = (campaign_location_setting_mixed_string_splitted[0] == "") ? 0 : campaign_location_setting_mixed_string_splitted[0];
		var campaing_location_price = (typeof campaign_location_setting_mixed_string_splitted[1] === "undefined") ? 0 : parseFloat(campaign_location_setting_mixed_string_splitted[1]);
		
		var campaign_banner_setting_mixed_string = $('#banner_prices_select').val();
		var campaign_banner_setting_mixed_string_splitted = campaign_banner_setting_mixed_string.split('||');
		var campaign_banner_quantity = (campaign_banner_setting_mixed_string_splitted[0] == "") ? 0 : campaign_banner_setting_mixed_string_splitted[0];
		var campaing_banner_price = (typeof campaign_banner_setting_mixed_string_splitted[1] === "undefined") ? 0 : parseFloat(campaign_banner_setting_mixed_string_splitted[1]);


		var campaign_length = ad_campaign_period;
		var campaign_price = ad_campaign_price;
		var keywords_length = campaign_keyword_length;
		var keywords_price = campaing_keyword_price;
		var categories_quantity = campaign_category_quantity;
		var categories_price = campaing_category_price;
		var locations_quantity = campaign_location_quantity;
		var locations_price = campaing_location_price;
		var banners_quantity = campaign_banner_quantity;
		var banners_price = campaing_banner_price;
		var ad_campaign_total = $('#ad_campaign_total').html();
		var login_token = $('#login_token').val();

		var keywords_array = [];
		var categories_array = [];
		var locations_array = [];

		selected_position = '';
		selected_position_price = 0;

		$('.keyword_name').each(function(){
			if($(this).text() != "")
				keywords_array.push($(this).text());
		});
		$('.category_name').each(function(){
			if($(this).text() != "")
				categories_array.push($(this).text());
		});
		$('.location_name').each(function(){
			if($(this).text() != "")
				locations_array.push($(this).text());
		});
		$('.banner_position_price').each(function(){
			if($(this).prop('checked') == true){
				selected_position = $(this).attr('id');
				selected_position_price = $(this).val();
			}
		});

		  
		var fd = new FormData();
		var file_data = $('#skyscraper_upload').prop('files')[0];  
		var file_data2 = $('#leader_board_upload').prop('files')[0];  
		var file_data3 = $('#banner_upload').prop('files')[0];  
		fd.append('operation_name','ad_submission');
		fd.append('campaign_length', campaign_length); 
		fd.append('campaign_price', campaign_price); 
		fd.append('keywords_length', keywords_length); 
		fd.append('keywords_price', keywords_price); 
		fd.append('categories_quantity', categories_quantity); 
		fd.append('categories_price', categories_price); 
		fd.append('locations_quantity', locations_quantity); 
		fd.append('locations_price', locations_price); 
		fd.append('banners_quantity', banners_quantity); 
		fd.append('banners_price', banners_price); 
		fd.append('keywords_array', JSON.stringify(keywords_array)); 
		fd.append('categories_array', JSON.stringify(categories_array)); 
		fd.append('locations_array', JSON.stringify(locations_array)); 
		fd.append('ad_campaign_total', ad_campaign_total);
		fd.append('selected_position', selected_position);
		fd.append('selected_position_price', selected_position_price);
		fd.append('login_token', login_token);
		fd.append('file',file_data);
		fd.append('file2',file_data2);
		fd.append('file3',file_data3);
		
		$.ajax({
			url:base_url + 'ajax/vendors/settings.php',
			method:"post",
			data:fd,
			cache:false,
			contentType:false,
			processData:false,
			success:function(response) {
				response = JSON.parse(response);
				if(response.errors != null){
					$('#submission_message').fadeIn();
					$('#submission_error_messages').html('');
					response.errors.forEach(function(error){
						$('#submission_error_messages').append('<p class="text_error fs_14 lh_20">- '+error+'</p>');
					});
				}else{
					var campaign_length_to_show = '';
					var banner_position_to_show = '';
					if(response.data.campaign_length == 'one_day'){
						campaign_length_to_show = 'One Day';
					}else if(response.data.campaign_length == 'one_week'){
						campaign_length_to_show = 'One Week';
					}else if(response.data.campaign_length == 'three_months'){
						campaign_length_to_show = 'Three Months';
					}else if(response.data.campaign_length == 'one_year'){
						campaign_length_to_show = 'One Year';
					}

					if(response.data.selected_position == 'banner_top_left'){
						banner_position_to_show = "Banner top left";
					}else if(response.data.selected_position == 'banner_top_right'){
						banner_position_to_show = "Banner top right";
					}else if(response.data.selected_position == 'banner_bottom_left'){
						banner_position_to_show = "Banner bottom left";
					}else if(response.data.selected_position == 'banner_bottom_right'){
						banner_position_to_show = "Banner bottom right";
					}else if(response.data.selected_position == 'banners_two_top'){
						banner_position_to_show = "Banners two top";
					}else if(response.data.selected_position == 'banners_two_bottom'){
						banner_position_to_show = "Banners two bottom";
					}else if(response.data.selected_position == 'banners_all_four'){
						banner_position_to_show = "Banners all four";
					}	
					
					var item_price = '';
					item_price += '<p style="font-size: 12px;line-height: 12px; margin: 10px 0px;">Campaign Length : '+campaign_length_to_show+' ($'+response.data.campaign_price+')</p>';
					item_price += '<p style="font-size: 12px;line-height: 12px; margin: 10px 0px;">Number of Keywords : '+response.data.keywords_length+' ($'+response.data.keywords_price+')</p>';
					item_price += '<p style="font-size: 12px;line-height: 12px; margin: 10px 0px;">Number of Categories : '+response.data.categories_quantity+' ($'+response.data.categories_price+')</p>';
					item_price += '<p style="font-size: 12px;line-height: 12px; margin: 10px 0px;">Number of Locations : '+response.data.locations_quantity+' ($'+response.data.locations_price+')</p>';
					item_price += '<p style="font-size: 12px;line-height: 12px; margin: 10px 0px;">Number of Banners : '+response.data.banners_quantity+' ($'+response.data.banners_price+')</p>';
					item_price += '<p style="font-size: 12px;line-height: 12px; margin: 10px 0px;">Banner Position : '+banner_position_to_show+' ($'+response.data.selected_position_price+')</p>';
					item_price += '<p style="font-size: 12px;line-height: 12px; margin: 10px 0px;">Total : ($'+response.data.ad_campaign_total+')</p>';
					$('#show_item_price_cart').append(item_price);
					$('.shopping-cart-modal').fadeIn();
				}
			},
			error:function(error){
				console.log(error);
			},
			beforeSend: function(){
				// Show image container
				$('#lds-dual-ring-container').fadeIn();
			},
			complete:function(data){
				// Hide image container
				$('#lds-dual-ring-container').fadeOut();
			}
		});
	});
});


function set_ad_campaign_total(){
	var ad_campaign_length_mixed_string = $('#campaign_length_select').val();
	var ad_campaign_length_mixed_string_splitted = ad_campaign_length_mixed_string.split('||');
	var ad_campaign_period = ad_campaign_length_mixed_string_splitted[0];
	var ad_campaign_price = (typeof ad_campaign_length_mixed_string_splitted[1] === "undefined") ? 0 : parseFloat(ad_campaign_length_mixed_string_splitted[1]);

	var campaign_keyword_setting_mixed_string = $('#campaign_keyword_prices_select').val();
	var campaign_keyword_setting_mixed_string_splitted = campaign_keyword_setting_mixed_string.split('||');
	var campaign_keyword_length = (typeof campaign_keyword_setting_mixed_string_splitted[0] === "undefined") ? 0 : campaign_keyword_setting_mixed_string_splitted[0];
	var campaing_keyword_price = (typeof campaign_keyword_setting_mixed_string_splitted[1] === "undefined") ? 0 : parseFloat(campaign_keyword_setting_mixed_string_splitted[1]);

	var campaign_category_setting_mixed_string = $('#category_prices_select').val();
	var campaign_category_setting_mixed_string_splitted = campaign_category_setting_mixed_string.split('||');
	var campaign_category_length = (typeof campaign_category_setting_mixed_string_splitted[0] === "undefined") ? 0 : campaign_category_setting_mixed_string_splitted[0];
	var campaing_category_price = (typeof campaign_category_setting_mixed_string_splitted[1] === "undefined") ? 0 : parseFloat(campaign_category_setting_mixed_string_splitted[1]);

	var campaign_location_setting_mixed_string = $('#location_prices_select').val();
	var campaign_location_setting_mixed_string_splitted = campaign_location_setting_mixed_string.split('||');
	var campaign_location_length = (typeof campaign_location_setting_mixed_string_splitted[0] === "undefined") ? 0 : campaign_location_setting_mixed_string_splitted[0];
	var campaing_location_price = (typeof campaign_location_setting_mixed_string_splitted[1] === "undefined") ? 0 : parseFloat(campaign_location_setting_mixed_string_splitted[1]);
	
	var campaign_banner_setting_mixed_string = $('#banner_prices_select').val();
	var campaign_banner_setting_mixed_string_splitted = campaign_banner_setting_mixed_string.split('||');
	var campaign_banner_length = (typeof campaign_banner_setting_mixed_string_splitted[0] === "undefined") ? 0 : campaign_banner_setting_mixed_string_splitted[0];
	var campaing_banner_price = (typeof campaign_banner_setting_mixed_string_splitted[1] === "undefined") ? 0 : parseFloat(campaign_banner_setting_mixed_string_splitted[1]);
	
	var banner_place_price = 0;
	
	$('.banner_position_price').each(function(){
		if($(this).prop('checked') == true){
			banner_place_price = parseFloat($(this).val());
		}
	});

	if(campaign_keyword_setting_mixed_string == ''){
		$('#max_keyword_number_to_show').text(0);
	}else{
		$('#max_keyword_number_to_show').text(campaign_keyword_length);
	}


	var campaign_total = ad_campaign_price + campaing_keyword_price + campaing_category_price + campaing_location_price + campaing_banner_price + banner_place_price;
	$('#ad_campaign_total').text(campaign_total)
}
