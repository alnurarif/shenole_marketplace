<?php
session_start();
require "../start.php";
use Shenole_project\helpers\Validator;
use Shenole_project\models\Client;
use Shenole_project\utils\RandomStringGenerator;
use Shenole_project\helpers\UserHelper;
use Shenole_project\repositories\ClientRepository;
use Shenole_project\helpers\MyHelpers;

$isClientLoggedIn = UserHelper::isUserLoggedIn($_SESSION, 'client', new ClientRepository);

if(!$isClientLoggedIn){
	header("Location: ".SITE_LINK."login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<?php MyHelpers::includeWithVariables('../layouts/head_section.php', [], $print = true); ?>
<body>
	<div class="genesis-container">
		<?php 
		MyHelpers::includeWithVariables('../layouts/top_nav.php', ['isClientLoggedIn' => $isClientLoggedIn], $print = true);
		?>
		<div class="main-body-content">
			<section class="section-type-01">
                <div class="ad-space-container-160">
                    <div class="ad-space-type01-desktop">
                        <!-- Ad Space (160 x 600) -->
                    </div>
                    <div class="ad-space-type01-desktop">
                        <!-- Ad Space (160 x 600) -->
                    </div>
                </div>
                <!-- <div class="ad-space-type01-mobile">

                </div> -->
                <div class="content-container-center">
                    <div class="vendor-profile-container">
                        <div class="vendor-profile-header-container">
                        </div>
                        <div class="profile-section">
                            <h2>Transactions</h2>
                            <div class="custom-table-container-01">
                                <div class="custom-table-heading-01">
                                    <div class="table-row-content-container-date">
                                        date
                                    </div>
                                    <div class="table-row-content-container-vendor">
                                        vendor
                                    </div>
                                    <div class="table-row-content-container-item">
                                        item
                                    </div>
                                    <div class="table-row-content-container-qty">
                                        quantity
                                    </div>
                                    <div class="table-row-content-container-price">
                                        price
                                    </div>
                                </div>
                                <div class="custom-table-row-01">
                                    <div class="table-row-content-container-date">
                                        09/28/22
                                    </div>
                                    <div class="table-row-content-container-vendor">
                                        Music Man Stan
                                    </div>
                                    <div class="table-row-content-container-item">
                                        Lorem ipsum dolor...
                                    </div>
                                    <div class="table-row-content-container-qty">
                                        2
                                    </div>
                                    <div class="table-row-content-container-price">
                                        $8,995.00
                                    </div>
                                </div>
                            </div>
                            <div class="spacer-10px"></div>
                            <div class="spacer-10px"></div>
                            <div class="pagination-container">
                                <div class="pagination-link-current">1</div>
                                <a href="" class="white-text"><div class="pagination-link">2</div></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="ad-space-type01-mobile">

                </div> -->
                <div class="ad-space-container-160">
                    <div class="ad-space-type01-desktop">
                        <!-- Ad Space (160 x 600) -->
                    </div>
                    <div class="ad-space-type01-desktop">
                        <!-- Ad Space (160 x 600) -->
                    </div>
                </div>
            </section>
		</div>
		<?php 
        MyHelpers::includeWithVariables('../layouts/footer.php', [], $print = true);
        ?>
	</div>
	<script src="../js/jquery.min.js"></script>
	<script src="../js/pages/index_page.js"></script>
</body>
</html>