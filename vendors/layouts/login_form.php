<div class="fix w_400 div_mid p_10">
	<?php if($msg!=''){?>
	<div class="fix full h_50">
		<p class="fs_14 lh_50 font_bold text_error pl_5 pr_5 pt_10 pb_10"><?php echo $msg; ?></p>
	</div>
	<?php } ?>
	<form method="post" action="login.php">
		<input type="hidden" name="operation_name" value="customer_login">
		<input 
		class="full h_30 full bt_1 br_1 bb_1 bl_1 border_solid border_ash pl_5 pr_5 border_box" 
		type="text" 
		name="email" 
		id="email" 
		placeholder="Enter email" 
		value="<?php echo (isset($_POST['email']) ? $_POST['email'] : ''); ?>"
		/>
		<input 
		class="full h_30 full bt_1 br_1 bb_1 bl_1 border_solid border_ash pl_5 pr_5 border_box mt_10" 
		type="password" 
		name="password" 
		id="password" 
		placeholder="*********" 
		value="<?php echo (isset($_POST['password']) ? $_POST['password'] : ''); ?>"
		/>
		<button
		class="cursor_pointer display_block mt_10 full bt_1 br_1 bb_1 bl_1 border_solid border_dark_ash h_40 text_dark_grey bg_very_light_ash textcenter fs_14 font_bold" 
		type="submit" 
		id="check_digital_name_button">Login</button>
	</form>
	<a class="fs_14 text_sky_blue2 font_bold lh_22" href="forget_password.php">Forgot Password?</a>
	<!-- <p class="fs_14 lh_22 font_bold text_dark_ash pl_5 pr_5 pt_10 pb_10">Are you new to this site? Please <a href="sign_up.php" class="fs_14 lh_22 font_bold text_sky_blue2">Sign Up</a></p> -->
</div>