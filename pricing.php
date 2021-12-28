<?php
session_start();
require "start.php";
use Shenole_project\helpers\Validator;
use Shenole_project\models\Client;
use Shenole_project\models\Vendor;
use Shenole_project\repositories\ClientRepository;
use Shenole_project\repositories\VendorRepository;
use Shenole_project\repositories\StaffRepository;
use Shenole_project\repositories\MajesticRepository;
use Shenole_project\helpers\UserHelper;
use Shenole_project\utils\RandomStringGenerator;
use Shenole_project\helpers\MyHelpers;

$isClientLoggedIn = UserHelper::isUserLoggedIn($_SESSION, 'client', new ClientRepository);
$isVendorLoggedIn = UserHelper::isUserLoggedIn($_SESSION, 'vendor', new VendorRepository);
$isStaffLoggedIn = UserHelper::isUserLoggedIn($_SESSION, 'staff', new StaffRepository);
$isMajesticLoggedIn = UserHelper::isUserLoggedIn($_SESSION, 'majestic', new MajesticRepository);

$membership01 = 0;
$membership02 = 0;
$membership03 = 0;
$membership04 = 0;

?>
<!DOCTYPE html>
<html lang="en">
<?php MyHelpers::includeWithVariables('./layouts/head_section.php', [], $print = true); ?>
<body>
	<div class="genesis-container">
		<?php 
		MyHelpers::includeWithVariables('./layouts/top_nav.php', ['isClientLoggedIn' => $isClientLoggedIn, 'isVendorLoggedIn' => $isVendorLoggedIn, 'isStaffLoggedIn' => $isStaffLoggedIn, 'isMajesticLoggedIn' => $isMajesticLoggedIn], $print = true);
		?>
		<div class="fix full container dual-signup-container">
			<div class="fix fifty_percent div_mid">
				<div id="membership-cards-container">
					<div class="membership-card">
						<h3 class="membership-card-title"><?php echo $membership01; ?></h3>
					</div>
					<div class="membership-card">
						<h3 class="membership-card-title"><?php echo $membership02; ?></h3>
					</div>
					<div class="membership-card">
						<h3 class="membership-card-title"><?php echo $membership03; ?></h3>
					</div>
					<div class="membership-card">
						<h3 class="membership-card-title"><?php echo $membership04; ?></h3>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>