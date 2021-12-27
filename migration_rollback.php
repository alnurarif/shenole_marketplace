<?php
require "start.php";
use Illuminate\Database\Capsule\Manager as Capsule;
Capsule::schema()->dropIfExists('clients');
Capsule::schema()->dropIfExists('vendors');
Capsule::schema()->dropIfExists('vendor_locations');
Capsule::schema()->dropIfExists('vendor_categories');
Capsule::schema()->dropIfExists('staff');
Capsule::schema()->dropIfExists('majestic');
Capsule::schema()->dropIfExists('client_settings');
Capsule::schema()->dropIfExists('vendor_settings');
Capsule::schema()->dropIfExists('products_services');
Capsule::schema()->dropIfExists('messages');
Capsule::schema()->dropIfExists('support_tickets');
Capsule::schema()->dropIfExists('knowledge_articles');
Capsule::schema()->dropIfExists('states');

