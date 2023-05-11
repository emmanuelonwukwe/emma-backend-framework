<?php
    use App\Http\Controllers\RegisterController;
    use App\Http\Controllers\LoginController;
    use App\Http\Controllers\UserController;

    require_once __DIR__.'/vendor/autoload.php';

    authPanelChecker();

    //registration route
    if(isset($_POST['action']) && $_POST["action"] == 'register') {
        RegisterController::signUp();
        return;
    }

    //login route
    if(isset($_POST['action']) && $_POST["action"] == 'login') {
        LoginController::authenticate();
        return;
    }

    //logout route
    if(isset($_POST['action']) && $_POST["action"] == 'logout') {
        authPanelChecker('user');
        LoginController::logout();
        return;
    }

    //get user information route
    if(isset($_POST['action']) && $_POST["action"] == 'auth_user_data') {
        authPanelChecker('user');
        UserController::data();
        return;
    }

    //wrong api route call
    echo responseJson([
        "message" => "invalid api route set"
    ], 400);
    return;
  
