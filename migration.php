<?php
require "start.php";
use Illuminate\Database\Capsule\Manager as Capsule;
use Shenole_project\models\Majestic;
use Shenole_project\models\State;
use Shenole_project\models\Client;
use Shenole_project\models\Vendor;
use Shenole_project\utils\RandomStringGenerator;

// Capsule::schema()->create('clients', function ($table) {
//     $table->increments('id');
//     $table->unsignedInteger('state_id')->nullable();
//     $table->string('uuid',50)->unique();
//     $table->string('user_type',20)->nullable();
//     $table->string('membership_level',20)->nullable();
//     $table->string('first_name',100)->nullable();
//     $table->string('last_name',100)->nullable();
//     $table->string('interests',150)->nullable();
//     $table->string('location_city',100)->nullable();
//     $table->string('location_zip_code',100)->nullable();
//     $table->string('email',100)->unique();
//     $table->string('terms_of_service',100)->nullable();
//     $table->string('password',100);
//     $table->string('login_token',100)->nullable();
//     $table->string('account_status',20)->nullable()->default('A');
//     $table->timestamps();
// });

// Capsule::schema()->create('vendors', function ($table) {
//     $table->increments('id');
//     $table->string('uuid',50)->unique();
//     $table->string('user_type',20)->nullable();
//     $table->string('membership_level',20)->nullable();
//     $table->string('first_name',100)->nullable();
//     $table->string('last_name',100)->nullable();
//     $table->string('email',100)->unique();
//     $table->string('terms_of_service',100)->nullable();
//     $table->string('password',100);
//     $table->string('login_token',100)->nullable();
//     $table->text('vendor_description',5000)->nullable();
//     $table->string('account_status',20)->nullable()->default('A');
//     $table->timestamps();
// });

// Capsule::schema()->create('vendor_locations', function ($table) {
//     $table->unsignedInteger('vendor_id');
//     $table->string('location_state',50)->nullable();
//     $table->string('location_city',50)->nullable();
//     $table->string('location_zip_code',50)->nullable();
//     $table->timestamps();
// });

// Capsule::schema()->create('master_vendor_categories', function ($table) {
// 	$table->increments('id',5);
//     $table->string('name',50)->nullable();
//     $table->timestamps();
// });

// Capsule::schema()->create('staff', function ($table) {
//     $table->increments('id');
//     $table->unsignedInteger('state_id')->nullable();
//     $table->string('uuid',50)->unique();
//     $table->string('user_type',20)->nullable();
//     $table->string('first_name',100)->nullable();
//     $table->string('last_name',100)->nullable();
//     $table->string('location_city',50)->nullable();
//     $table->string('location_zip_code',50)->nullable();
//     $table->string('email',100)->unique();
//     $table->string('password',100);
//     $table->string('login_token',100)->nullable();
//     $table->string('account_status',20)->nullable()->default('A');
//     $table->timestamps();
// });

// Capsule::schema()->create('majestic', function ($table) {
//     $table->increments('id');
//     $table->string('uuid',50)->unique();
//     $table->string('user_type',20)->nullable();
//     $table->string('first_name',100)->nullable();
//     $table->string('last_name',100)->nullable();
//     $table->string('email',100)->unique();
//     $table->string('password',100);
//     $table->string('login_token',100)->nullable();
//     $table->timestamps();
// });

// Capsule::schema()->create('client_settings', function ($table) {
//     $table->increments('id');
//     $table->string('uuid',50)->unique();
//     $table->enum('ads', ['on', 'off']);
//     $table->enum('favorite_vendors', ['on', 'off']);
//     $table->timestamps();
// });

// Capsule::schema()->create('vendor_settings', function ($table) {
//     $table->increments('id');
//     $table->string('uuid',50)->unique();
//     $table->string('paypal_client_id',100)->nullable();
//     $table->enum('ads', ['on', 'off']);
//     $table->timestamps();
// });
// Capsule::schema()->create('products_services', function ($table) {
//     $table->increments('id');
//     $table->string('uuid',50)->unique();
//     $table->string('product_id',50)->nullable();
//     $table->string('product_name',100)->nullable();
//     $table->string('product_description',200)->nullable();
//     $table->unsignedDecimal('product_price', $precision = 10, $scale = 2)->default(0)->nullable();
//     $table->string('product_status',20)->nullable();
//     $table->timestamps();
// });

// Capsule::schema()->create('messages', function ($table) {
//     $table->increments('id');
//     $table->string('uuid',50)->unique();
//     $table->string('message_id',50)->nullable();
//     $table->string('send_to',10)->nullable();
//     $table->dateTime('send_date_time', $precision = 0);
//     $table->string('subject',60)->nullable();
//     $table->text('body')->nullable();
//     $table->string('message_status',20)->nullable();
//     $table->timestamps();
// });
// Capsule::schema()->create('support_tickets', function ($table) {
//     $table->increments('id');
//     $table->string('uuid',50)->unique();
//     $table->string('ticket_id',50)->nullable();
//     $table->string('send_to',10)->nullable();
//     $table->dateTime('send_date_time', $precision = 0);
//     $table->string('subject',60)->nullable();
//     $table->text('body')->nullable();
//     $table->text('assigned_to',20)->nullable();
//     $table->string('ticket_status',20)->nullable();
//     $table->timestamps();
// });
// Capsule::schema()->create('knowledge_articles', function ($table) {
//     $table->increments('id');
//     $table->string('uuid',50)->unique();
//     $table->string('article_id',50)->nullable();
//     $table->string('send_to',10)->nullable();
//     $table->dateTime('created_date_time', $precision = 0);
//     $table->dateTime('updated_date_time', $precision = 0);
//     $table->string('title',200)->nullable();
//     $table->longText('body',20)->nullable();
//     $table->enum('show', ['yes', 'no']);
// });
// Capsule::schema()->create('states', function ($table) {
//     $table->increments('id');
//     $table->string('name',100)->unique();
// });

// $generator = new RandomStringGenerator;
// $tokenLength = 5;
// $random_string = $generator->generate($tokenLength);
// $majestic = new Majestic;
// $majestic->uuid = 'majestic_'.date("Ymdhis").'_'.$random_string;
// $majestic->first_name = "Justin";
// $majestic->last_name = "Dane";
// $majestic->email = "justin@yahoo.com";
// $majestic->password = "Justin@123";
// $majestic->save();

// $generator = new RandomStringGenerator;
// $tokenLength = 5;
// $random_string = $generator->generate($tokenLength);
// $majestic = new Majestic;
// $majestic->uuid = 'majestic_'.date("Ymdhis").'_'.$random_string;
// $majestic->first_name = "Al-Nur";
// $majestic->last_name = "Arif";
// $majestic->email = "alnur@yahoo.com";
// $majestic->password = "Alnur@123";
// $majestic->save();
// 
// 
// $states = ["Arizona", "Arkansas", "California", "Colorado", "Connecticut", "Delaware", "District of Columbia", "Florida", "Georgia", "Hawaii", "Idaho", "Illinois", "Indiana", "Iowa", "Kansas", "Kentucky", "Louisiana", "Maine", "Montana", "Nebraska", "Nevada", "New Hampshire", "New Jersey", "New Mexico", "New York", "North Carolina", "North Dakota", "Ohio", "Oklahoma", "Oregon", "Maryland", "Massachusetts", "Michigan", "Minnesota", "Mississippi", "Missouri", "Pennsylvania", "Rhode Island", "South Carolina", "South Dakota", "Tennessee", "Texas", "Utah", "Vermont", "Virginia", "Washington", "West Virginia", "Wisconsin", "Wyoming"];
// foreach($states as $state){
//     $st = new State;
//     $st->name = $state;
//     $st->save();    
// }
// $ids = [1,2];
// foreach($ids as $id){
//     $client = Client::find($id);
//     $client->account_status = 'A';
//     $client->save();
//     $vendor = Vendor::find($id);
//     $vendor->account_status = 'A';
//     $vendor->save();
// }
// Capsule::schema()->table('staff', function ($table) {
//     $table->string('account_status',20)->nullable()->default('A')->change();
// });
// Capsule::schema()->table('vendors', function ($table) {
//     $table->string('account_status',20)->nullable()->default('A')->change();
// });
// Capsule::schema()->table('clients', function ($table) {
//     $table->string('account_status',20)->nullable()->default('A')->change();
// });

// $servername = "localhost";
// $username = "eventonestop_majestic";
// $password = "Z(?*E!pUfq5K";
// $dbname = "eventonestop_primary";

// // Create connection
// $conn = new mysqli($servername, $username, $password, $dbname);
// // Check connection
// if ($conn->connect_error) {
//   die("Connection failed: " . $conn->connect_error);
// }

// $sql = "ALTER TABLE `staff` CHANGE `account_status` `account_status` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A'";
// if ($conn->query($sql) === TRUE) {
//   echo "New record created successfully";
// }
// $sql = "ALTER TABLE `vendors` CHANGE `account_status` `account_status` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A'";
// if ($conn->query($sql) === TRUE) {
//   echo "New record created successfully";
// }
// $sql = "ALTER TABLE `clients` CHANGE `account_status` `account_status` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'A'";
// if ($conn->query($sql) === TRUE) {
//   echo "New record created successfully";
// }




// $conn->close();



// [
// {
// "name": "Alabama",
// "abbreviation": "AL"
// },
// {
// "name": "Alaska",
// "abbreviation": "AK"
// },
// {
// "name": "American Samoa",
// "abbreviation": "AS"
// },
// {
// "name": "Arizona",
// "abbreviation": "AZ"
// },
// {
// "name": "Arkansas",
// "abbreviation": "AR"
// },
// {
// "name": "California",
// "abbreviation": "CA"
// },
// {
// "name": "Colorado",
// "abbreviation": "CO"
// },
// {
// "name": "Connecticut",
// "abbreviation": "CT"
// },
// {
// "name": "Delaware",
// "abbreviation": "DE"
// },
// {
// "name": "District Of Columbia",
// "abbreviation": "DC"
// },
// {
// "name": "Federated States Of Micronesia",
// "abbreviation": "FM"
// },
// {
// "name": "Florida",
// "abbreviation": "FL"
// },
// {
// "name": "Georgia",
// "abbreviation": "GA"
// },
// {
// "name": "Guam",
// "abbreviation": "GU"
// },
// {
// "name": "Hawaii",
// "abbreviation": "HI"
// },
// {
// "name": "Idaho",
// "abbreviation": "ID"
// },
// {
// "name": "Illinois",
// "abbreviation": "IL"
// },
// {
// "name": "Indiana",
// "abbreviation": "IN"
// },
// {
// "name": "Iowa",
// "abbreviation": "IA"
// },
// {
// "name": "Kansas",
// "abbreviation": "KS"
// },
// {
// "name": "Kentucky",
// "abbreviation": "KY"
// },
// {
// "name": "Louisiana",
// "abbreviation": "LA"
// },
// {
// "name": "Maine",
// "abbreviation": "ME"
// },
// {
// "name": "Marshall Islands",
// "abbreviation": "MH"
// },
// {
// "name": "Maryland",
// "abbreviation": "MD"
// },
// {
// "name": "Massachusetts",
// "abbreviation": "MA"
// },
// {
// "name": "Michigan",
// "abbreviation": "MI"
// },
// {
// "name": "Minnesota",
// "abbreviation": "MN"
// },
// {
// "name": "Mississippi",
// "abbreviation": "MS"
// },
// {
// "name": "Missouri",
// "abbreviation": "MO"
// },
// {
// "name": "Montana",
// "abbreviation": "MT"
// },
// {
// "name": "Nebraska",
// "abbreviation": "NE"
// },
// {
// "name": "Nevada",
// "abbreviation": "NV"
// },
// {
// "name": "New Hampshire",
// "abbreviation": "NH"
// },
// {
// "name": "New Jersey",
// "abbreviation": "NJ"
// },
// {
// "name": "New Mexico",
// "abbreviation": "NM"
// },
// {
// "name": "New York",
// "abbreviation": "NY"
// },
// {
// "name": "North Carolina",
// "abbreviation": "NC"
// },
// {
// "name": "North Dakota",
// "abbreviation": "ND"
// },
// {
// "name": "Northern Mariana Islands",
// "abbreviation": "MP"
// },
// {
// "name": "Ohio",
// "abbreviation": "OH"
// },
// {
// "name": "Oklahoma",
// "abbreviation": "OK"
// },
// {
// "name": "Oregon",
// "abbreviation": "OR"
// },
// {
// "name": "Palau",
// "abbreviation": "PW"
// },
// {
// "name": "Pennsylvania",
// "abbreviation": "PA"
// },
// {
// "name": "Puerto Rico",
// "abbreviation": "PR"
// },
// {
// "name": "Rhode Island",
// "abbreviation": "RI"
// },
// {
// "name": "South Carolina",
// "abbreviation": "SC"
// },
// {
// "name": "South Dakota",
// "abbreviation": "SD"
// },
// {
// "name": "Tennessee",
// "abbreviation": "TN"
// },
// {
// "name": "Texas",
// "abbreviation": "TX"
// },
// {
// "name": "Utah",
// "abbreviation": "UT"
// },
// {
// "name": "Vermont",
// "abbreviation": "VT"
// },
// {
// "name": "Virgin Islands",
// "abbreviation": "VI"
// },
// {
// "name": "Virginia",
// "abbreviation": "VA"
// },
// {
// "name": "Washington",
// "abbreviation": "WA"
// },
// {
// "name": "West Virginia",
// "abbreviation": "WV"
// },
// {
// "name": "Wisconsin",
// "abbreviation": "WI"
// },
// {
// "name": "Wyoming",
// "abbreviation": "WY"
// }
// ]