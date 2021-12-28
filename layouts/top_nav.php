<?php
$show_login_sign_up = true;
if(isset($isClientLoggedIn) && $isClientLoggedIn){
    $show_login_sign_up = false;
}
if(isset($isVendorLoggedIn) && $isVendorLoggedIn){
    $show_login_sign_up = false;
}
if(isset($isStaffLoggedIn) && $isStaffLoggedIn){
    $show_login_sign_up = false;
}
if(isset($isMajesticLoggedIn) && $isMajesticLoggedIn){
    $show_login_sign_up = false;
}
?>

<nav class="main-nav-desktop-02">
    <div class="nav-container-center">
        <div class="nav-logo-container-desktop">
            <a href="<?php echo SITE_LINK; ?>"><img src="<?php echo SITE_LINK; ?>images/company-logo.png" class="fluid-image" alt="Company logo for"></a>
        </div>
        <div class="nav-link-container-desktop">
            <ul class="nav-ul-desktop">
                <li class="nav-li-desktop"><a href="<?php echo SITE_LINK; ?>" class="nav-link-desktop">Home</a></li>
                <li class="nav-li-desktop"><a href="<?php echo SITE_LINK; ?>listings.php" class="nav-link-desktop">Listings</a></li>
                <li class="nav-li-desktop"><a href="<?php echo SITE_LINK; ?>how-it-works.php" class="nav-link-desktop">How It Works</a></li>
                <li class="nav-li-desktop"><a href="<?php echo SITE_LINK; ?>about-us.php" class="nav-link-desktop">About Us</a></li>
                <li class="nav-li-desktop"><a href="<?php echo SITE_LINK; ?>pricing.php" class="nav-link-desktop">Pricing</a></li>
                <!-- I would like these 2 links to only be visible if a client or vendor user is not logged in. They should never be visible for staff or majestic users -->
                <?php if($show_login_sign_up){ ?>
                <li class="nav-li-desktop"><a href="<?php echo SITE_LINK; ?>signup.php" class="nav-link-desktop">Sign Up</a></li>
                <li class="nav-li-desktop"><a href="<?php echo SITE_LINK; ?>login.php" class="nav-link-desktop">Login</a></li>
                <?php }else{ ?>
                    <?php if(isset($isMajesticLoggedIn) && $isMajesticLoggedIn){ ?>
                        <li class="nav-li-desktop"><a href="<?php echo SITE_LINK_MAJESTIC; ?>logout.php" class="nav-link-desktop">Logout</a></li>
                    <?php } ?>

                    <?php if(((isset($isMajesticLoggedIn) && !$isMajesticLoggedIn) || !isset($isMajesticLoggedIn)) && isset($isVendorLoggedIn) && $isVendorLoggedIn){ ?>
                        <li class="nav-li-desktop"><a href="<?php echo SITE_LINK_VENDOR; ?>logout.php" class="nav-link-desktop">Logout</a></li>
                    <?php } ?>

                    <?php if(((isset($isMajesticLoggedIn) && !$isMajesticLoggedIn) || !isset($isMajesticLoggedIn)) && isset($isClientLoggedIn) && $isClientLoggedIn){ ?>
                        <li class="nav-li-desktop"><a href="<?php echo SITE_LINK_CLIENT; ?>logout.php" class="nav-link-desktop">Logout</a></li>
                    <?php } ?>

                    <?php if(((isset($isMajesticLoggedIn) && !$isMajesticLoggedIn) || !isset($isMajesticLoggedIn)) && isset($isStaffLoggedIn) && $isStaffLoggedIn){ ?>
                        <li class="nav-li-desktop"><a href="<?php echo SITE_LINK_STAFF; ?>logout.php" class="nav-link-desktop">Logout</a></li>
                    <?php } ?>

                <?php } ?>
                <!-- I would like this 1 link to only be visible if a staff or majestic user is not logged in. It should never be visible for client or vendor users -->
                <!-- <li class="nav-li-desktop"><a href="<?php echo SITE_LINK; ?>login.php" class="nav-link-desktop">Login</a></li> -->
                <!-- I would like this link to only be visible if a user IS logged in. A conditional should be used so that client and vendor users land on "login.php", staff users land on "staff/login.php" and majestic users land on "majestic/login.php" -->
                <!-- <li class="nav-li-desktop"><a href="" class="nav-link-desktop">Logout</a></li> -->
            </ul>
        </div>
    </div>
</nav>

<!-- this is majestic admin nav -->
<?php if(isset($isMajesticLoggedIn) && $isMajesticLoggedIn){ ?>
<nav class="admin-nav-desktop-02" id="majestic-admin-nav">
    <div class="nav-container-center">
        <div class="nav-admin-link-container-desktop">
            <ul class="nav-admin-ul-desktop">
                <li class="nav-admin-li-desktop"><a href="<?php echo SITE_LINK_MAJESTIC; ?>dashboard.php" class="nav-admin-link-desktop">Dashboard</a></li>
                <li class="nav-admin-li-desktop"><a href="<?php echo SITE_LINK_MAJESTIC; ?>staff-listing.php" class="nav-admin-link-desktop">Staff</a></li>
                <li class="nav-admin-li-desktop"><a href="<?php echo SITE_LINK_MAJESTIC; ?>client-listing.php" class="nav-admin-link-desktop">Clients</a></li>
                <li class="nav-admin-li-desktop"><a href="<?php echo SITE_LINK_MAJESTIC; ?>vendor-listing.php" class="nav-admin-link-desktop">Vendors</a></li>
                <li class="nav-admin-li-desktop"><a href="<?php echo SITE_LINK_MAJESTIC; ?>settings.php" class="nav-admin-link-desktop">Settings</a></li>
                <li class="nav-admin-li-desktop"><a href="<?php echo SITE_LINK_MAJESTIC; ?>messages.php" class="nav-admin-link-desktop">Messages</a></li>
                <li class="nav-admin-li-desktop"><a href="<?php echo SITE_LINK_KNOWLEDGEBASE; ?>knowledgebase-home.php" class="nav-admin-link-desktop">Knowledge Base</a></li>
                <li class="nav-admin-li-desktop"><a href="<?php echo SITE_LINK_MAJESTIC; ?>support-tickets.php" class="nav-admin-link-desktop">Support Tickets</a></li>
            </ul>
        </div>
    </div>
</nav>
<?php } ?>

<!-- this is vendor admin nav -->
<?php if((isset($isMajesticLoggedIn) && !$isMajesticLoggedIn || !isset($isMajesticLoggedIn)) && isset($isVendorLoggedIn) && $isVendorLoggedIn){ ?>
<nav class="admin-nav-desktop-02" id="vendor-admin-nav">
    <div class="nav-container-center">
        <div class="nav-admin-link-container-desktop">
            <ul class="nav-admin-ul-desktop">
                <li class="nav-admin-li-desktop"><a href="<?php echo SITE_LINK_VENDOR; ?>dashboard.php" class="nav-admin-link-desktop">Dashboard</a></li>
                <li class="nav-admin-li-desktop"><a href="<?php echo SITE_LINK_VENDOR; ?>transactions.php" class="nav-admin-link-desktop">Transactions</a></li>
                <li class="nav-admin-li-desktop"><a href="<?php echo SITE_LINK_VENDOR; ?>clients.php" class="nav-admin-link-desktop">Client List</a></li>
                <li class="nav-admin-li-desktop"><a href="<?php echo SITE_LINK_VENDOR; ?>edit-profile.php" class="nav-admin-link-desktop">Edit Profile</a></li>
                <li class="nav-admin-li-desktop"><a href="<?php echo SITE_LINK_VENDOR; ?>settings.php" class="nav-admin-link-desktop">Settings</a></li>
                <li class="nav-admin-li-desktop"><a href="<?php echo SITE_LINK_VENDOR; ?>messages.php" class="nav-admin-link-desktop">Messages</a></li>
                <li class="nav-admin-li-desktop"><a href="<?php echo SITE_LINK_KNOWLEDGEBASE; ?>knowledgebase-home.php" class="nav-admin-link-desktop">Knowledge Base</a></li>
                <li class="nav-admin-li-desktop"><a href="<?php echo SITE_LINK_VENDOR; ?>support-tickets.php" class="nav-admin-link-desktop">Support Tickets</a></li>
            </ul>
        </div>
    </div>
</nav>
<?php } ?>

<!-- this is client admin nav -->
<?php if((isset($isMajesticLoggedIn) && !$isMajesticLoggedIn || !isset($isMajesticLoggedIn)) && isset($isClientLoggedIn) && $isClientLoggedIn){ ?>
<nav class="admin-nav-desktop-02" id="client-admin-nav">
    <div class="nav-container-center">
        <div class="nav-admin-link-container-desktop">
            <ul class="nav-admin-ul-desktop">
                <li class="nav-admin-li-desktop"><a href="<?php echo SITE_LINK_CLIENT; ?>dashboard.php" class="nav-admin-link-desktop">Dashboard</a></li>
                <li class="nav-admin-li-desktop"><a href="<?php echo SITE_LINK_CLIENT; ?>transactions.php" class="nav-admin-link-desktop">Transactions</a></li>
                <li class="nav-admin-li-desktop"><a href="<?php echo SITE_LINK_CLIENT; ?>vendors.php" class="nav-admin-link-desktop">Vendor List</a></li>
                <li class="nav-admin-li-desktop"><a href="<?php echo SITE_LINK_CLIENT; ?>settings.php" class="nav-admin-link-desktop">Settings</a></li>
                <li class="nav-admin-li-desktop"><a href="<?php echo SITE_LINK_CLIENT; ?>messages.php" class="nav-admin-link-desktop">Messages</a></li>
                <li class="nav-admin-li-desktop"><a href="<?php echo SITE_LINK_KNOWLEDGEBASE; ?>knowledgebase-home.php" class="nav-admin-link-desktop">Knowledge Base</a></li>
                <li class="nav-admin-li-desktop"><a href="<?php echo SITE_LINK_CLIENT; ?>support-tickets.php" class="nav-admin-link-desktop">Support Tickets</a></li>
            </ul>
        </div>
    </div>
</nav>
<?php } ?>


<!-- thi is staff admin nav -->
<?php if((isset($isMajesticLoggedIn) && !$isMajesticLoggedIn || !isset($isMajesticLoggedIn)) && isset($isStaffLoggedIn) && $isStaffLoggedIn){ ?>
<nav class="admin-nav-desktop-02" id="staff-admin-nav">
    <div class="nav-container-center">
        <div class="nav-admin-link-container-desktop">
            <ul class="nav-admin-ul-desktop">
                <li class="nav-admin-li-desktop"><a href="<?php echo SITE_LINK_STAFF; ?>dashboard.php" class="nav-admin-link-desktop">Dashboard</a></li>
                <li class="nav-admin-li-desktop"><a href="<?php echo SITE_LINK_STAFF; ?>client-listing.php" class="nav-admin-link-desktop">Clients</a></li>
                <li class="nav-admin-li-desktop"><a href="<?php echo SITE_LINK_STAFF; ?>vendor-listing.php" class="nav-admin-link-desktop">Vendors</a></li>
                <li class="nav-admin-li-desktop"><a href="<?php echo SITE_LINK_STAFF; ?>settings.php" class="nav-admin-link-desktop">Settings</a></li>
                <li class="nav-admin-li-desktop"><a href="<?php echo SITE_LINK_STAFF; ?>messages.php" class="nav-admin-link-desktop">Messages</a></li>
                <li class="nav-admin-li-desktop"><a href="<?php echo SITE_LINK_KNOWLEDGEBASE; ?>knowledgebase-home.php" class="nav-admin-link-desktop">Knowledge Base</a></li>
                <li class="nav-admin-li-desktop"><a href="<?php echo SITE_LINK_STAFF; ?>support-tickets.php" class="nav-admin-link-desktop">Support Tickets</a></li>
            </ul>
        </div>
    </div>
</nav>
<?php } ?>

<nav class="main-nav-mobile-02">
    <div class="nav-container-center-mobile">
        <div class="nav-link-container-mobile">
            <div class="burger-nav-container" id="secondary-nav-menu">
                <span class="burger-nav-line"></span>
                <span class="burger-nav-line center-burger-nav-line"></span>
                <span class="burger-nav-line"></span>
            </div>
        </div>
        <div class="nav-logo-container-mobile">
            <a href="<?php echo SITE_LINK; ?>"><img src="<?php echo SITE_LINK; ?>images/company-logo.png" class="fluid-image" alt="Company logo for"></a>
        </div>
        <div class="nav-link-container-mobile">
            <div class="burger-nav-container" id="primary-nav-menu">
                <span class="burger-nav-line"></span>
                <span class="burger-nav-line center-burger-nav-line"></span>
                <span class="burger-nav-line"></span>
            </div>
        </div>
    </div>
</nav>