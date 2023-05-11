<?php
    use App\Providers\AuthServiceProvider;

    $authUsernameKey = AuthServiceProvider::AUTH_CHECK_COLUMNS[0];
    $authPasswordKey = AuthServiceProvider::AUTH_CHECK_COLUMNS[1];

    //$GLOBALS["site_short_name"] = "sitename";
    //$GLOBALS["site_fullname"] = "sitename";
    $GLOBALS["server_protocol"] = strtolower(preg_replace("/\/.+/", '', $_SERVER["SERVER_PROTOCOL"]));
    $GLOBALS["site_url"] = "sitename.com";
    //$GLOBALS["site_email"] = "support@sitename.com";

    ////The session expiry time of the app. ie time now plus seconds
    $GLOBALS["token_expiry_secs"] = time() + 60*60*1;

    ////The table of the system statuses that can be encountered in transactions
    $GLOBALS["statuses"] = [
        //"new" => array("registration" => "registered", "login" => "logged"),
    ];

    ////system compensation plans and their percentages
    $GLOBALS["plans"] = [
        //"plan1" => array("min" => 10, "percent" => 0.5)
    ];

    ////The referral bonus percentages
    $GLOBALS["ref_bonus"] = [
        //"direct" => 10,
        //"level1" => 2
    ];

    ////The valid currencies accepted in this system
    $GLOBALS["system_currency_array"]=[
        //"ngn"=>array("full_name"=>"Naira", "short_name" => "NGN", "image"=>"assets/images/ngn.png")
    ];

    ////This is the database tables with their columns arrangements here
    $GLOBALS["DB_TABLES"] = [
        "users" => [$authUsernameKey, $authUsernameKey != "email" ? "email" : '' , "role", $authPasswordKey, "time_created", "date_created"],
        "reset_passwords" => ["email", "reset_key", "time_created", "date_created"],
        "personal_access_tokens" => [$authUsernameKey, "token", "default_name","valid", "time_created", "date_created"],
    ];

    ////system only allowed panel roles that can be registered on this system
    $GLOBALS["ROLE_LIST"] = ['user', 'admin'];

    $GLOBALS["MODES_ARRAY"] = ["development" => 1, "production" => 2];
    ////Below mode is the current mode of this application;
    $GLOBALS["MODE"] = $GLOBALS["MODES_ARRAY"]["development"];
    
    //Here is the admin register key that must be posted from the admin registeration form before role=admin can work in the form
    $GLOBALS["ADMIN_REGISTER_KEY"] = "123";

    //set the init set messages values
    //$GLOBALS["msgtype"]=$GLOBALS["msg"]="";
?>