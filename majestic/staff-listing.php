<?php
session_start();
require "../start.php";
use Shenole_project\helpers\Validator;
use Shenole_project\models\Staff;
use Shenole_project\utils\RandomStringGenerator;
use Shenole_project\helpers\UserHelper;
use Shenole_project\repositories\MajesticRepository;
use Shenole_project\helpers\MyHelpers;

$isMajesticLoggedIn = UserHelper::isUserLoggedIn($_SESSION, 'majestic', new MajesticRepository);

if(!$isMajesticLoggedIn){
	header("Location: ".SITE_LINK_MAJESTIC."login.php");
    exit();
}


$staff = Staff::get();

$show_all_staff = '';
foreach($staff as $staff){
	$status = '';
	if($staff->account_status == 'A'){
		$status = 'Active';
	}elseif($staff->account_status == 'I'){
		$status = 'Inactive';
	}elseif($staff->account_status == 'S'){
		$status = 'Suspended';
	}
	$show_all_staff .= '<tr>';
		$show_all_staff .= '<td class="fs_14 lh_22 text_dark_ash textcenter">'.$staff->first_name.'</td>';
		$show_all_staff .= '<td class="fs_14 lh_22 text_dark_ash textcenter">'.$staff->last_name.'</td>';
		$show_all_staff .= '<td class="fs_14 lh_22 text_dark_ash textcenter">'.$staff->email.'</td>';
		$show_all_staff .= '<td class="fs_14 lh_22 text_dark_ash textcenter">'.$status.'</td>';
		$show_all_staff .= '<td class="fs_14 lh_22 text_dark_ash textcenter">';
			$show_all_staff .= '<form action="staff-details.php" method="post" class="floatright">';
				$show_all_staff .= '<input type="hidden" name="staff_detail_show_operation" value="1"/>';
				$show_all_staff .= '<input type="hidden" name="staff_id" value="'.$staff->id.'"/>';
				$show_all_staff .= '<button type="submit" class="fs_14 lh_22 w_100 textcenter display_inline_block font_bold text_dark_ash bg_very_light_ash2 mt_5 mb_5 textcenter border_none cursor_pointer">See Details</button>';
			$show_all_staff .= '</form>';
			$show_all_staff .= '<form action="staff-edit.php" method="post" class="floatleft">';
				$show_all_staff .= '<input type="hidden" name="staff_id" value="'.$staff->id.'"/>';
				$show_all_staff .= '<input type="hidden" name="staff_edit_show_operation" value="1"/>';
				$show_all_staff .= '<button type="submit" class="fs_14 lh_22 w_100 textcenter display_inline_block font_bold text_dark_ash bg_very_light_ash2 mt_5 mb_5 textcenter border_none cursor_pointer">Edit</button>';
			$show_all_staff .= '</form>';
	$show_all_staff .= '</tr>';
}

?>

<!DOCTYPE html>
<html lang="en">
<?php MyHelpers::includeWithVariables('../layouts/head_section.php', [], $print = true); ?>
<body>
	<div class="genesis-container">
		<?php 
		MyHelpers::includeWithVariables('../layouts/top_nav.php', ['isMajesticLoggedIn' => $isMajesticLoggedIn], $print = true);
		?>
		<div class="main-body-content">
            <section>
                <div class="content-container-center">
					<div class="fix half floatleft"><h1 class="fs_30 lh_40 font_bold text_dark_ash mb_10 pl_5 pr_5">Staff Information</h1></div>
					<div class="fix half floatleft"><a class="display_inline_block lh_30 pl_16 pr_16 bg_very_light_ash2 text_dark_ash font_bold cursor_pointer mb_10 floatright mt_5" href="staff-add.php">Add Staff</a></div>
					
					
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
							<?php echo $show_all_staff; ?>
						</tbody>
					</table>
				</div>
			</section>
		</div>
	</div>
	<script src="../js/jquery.min.js"></script>
	<script src="../js/pages/index_page.js"></script>
</body>
</html>