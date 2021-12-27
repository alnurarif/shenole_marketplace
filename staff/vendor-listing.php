<?php
session_start();
require "../start.php";
use Shenole_project\helpers\Validator;
use Shenole_project\models\Vendor;
use Shenole_project\utils\RandomStringGenerator;
use Shenole_project\helpers\UserHelper;
use Shenole_project\repositories\StaffRepository;

$isStaffLoggedIn = UserHelper::isUserLoggedIn($_SESSION, 'staff', new StaffRepository);

if(!$isStaffLoggedIn){
	header("Location: ".SITE_LINK_STAFF."login.php");
    exit();
}

$vendors = Vendor::get();

$show_all_vendors = '';
foreach($vendors as $vendor){
	$status = '';
	if($vendor->account_status == 'A'){
		$status = 'Active';
	}elseif($vendor->account_status == 'I'){
		$status = 'Inactive';
	}elseif($vendor->account_status == 'S'){
		$status = 'Suspended';
	}
	$show_all_vendors .= '<tr>';
		$show_all_vendors .= '<td class="fs_14 lh_22 text_dark_ash textcenter">'.$vendor->first_name.'</td>';
		$show_all_vendors .= '<td class="fs_14 lh_22 text_dark_ash textcenter">'.$vendor->last_name.'</td>';
		$show_all_vendors .= '<td class="fs_14 lh_22 text_dark_ash textcenter">'.$vendor->email.'</td>';
		$show_all_vendors .= '<td class="fs_14 lh_22 text_dark_ash textcenter">'.$status.'</td>';
		$show_all_vendors .= '<td class="fs_14 lh_22 text_dark_ash textcenter">';
			$show_all_vendors .= '<form action="vendor-details.php" method="post" class="floatright">';
				$show_all_vendors .= '<input type="hidden" name="vendor_detail_show_operation" value="1"/>';
				$show_all_vendors .= '<input type="hidden" name="vendor_id" value="'.$vendor->id.'"/>';
				$show_all_vendors .= '<button type="submit" class="fs_14 lh_22 w_100 textcenter display_inline_block font_bold text_dark_ash bg_very_light_ash2 mt_5 mb_5 textcenter border_none cursor_pointer">See Details</button>';
			$show_all_vendors .= '</form>';
			$show_all_vendors .= '<form action="vendor-edit.php" method="post" class="floatleft">';
				$show_all_vendors .= '<input type="hidden" name="vendor_id" value="'.$vendor->id.'"/>';
				$show_all_vendors .= '<input type="hidden" name="vendor_edit_show_operation" value="1"/>';
				$show_all_vendors .= '<button type="submit" class="fs_14 lh_22 w_100 textcenter display_inline_block font_bold text_dark_ash bg_very_light_ash2 mt_5 mb_5 textcenter border_none cursor_pointer">Edit</button>';
			$show_all_vendors .= '</form>';
	$show_all_vendors .= '</tr>';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Market Place</title>
	<link rel="stylesheet" href="../css/style.css">
</head>
<body>
	<div class="fix full div_mid">
		<?php include('layouts/staff_panel_head.php'); ?>
		
		<div class="fix full">
			<?php include('layouts/staff_panel_left_menu.php'); ?>	
			
			<div class="fix floatleft eighty_percent pt_10 pr_10 pb_10 pl_10 border_box">
				<div class="fix full">
					<div class="fix half floatleft"><h1 class="fs_30 lh_40 font_bold text_dark_ash mb_10 pl_5 pr_5">Vendor Information</h1></div>
					<div class="fix half floatleft"><!-- <a class="display_inline_block lh_30 pl_16 pr_16 bg_very_light_ash2 text_dark_ash font_bold cursor_pointer mb_10 floatright mt_5" href="vendor-add.php">Add Vendor</a> --></div>
					
					
					<table class="full">
						<thead>
							<tr class="text_dark_ash">
								<th style="width: 25%; text-align: center;">First Name</th>
								<th style="width: 20%; text-align: center;">Last Name</th>
								<th style="width: 25%; text-align: center;">Email</th>
								<th style="width: 10%; text-align: center;">Status</th>
								<th style="width: 20%; text-align: center;">Details</th>
							</tr>							
						</thead>
						<tbody>
							<?php echo $show_all_vendors; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<script src="../js/jquery.min.js"></script>
	<script src="../js/pages/index_page.js"></script>
</body>
</html>