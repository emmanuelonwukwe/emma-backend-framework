<?php
    use Utility\Db;
    use App\Providers\RedirectServiceProvider;
    use App\Providers\AuthServiceProvider;
    use App\Models\PAToken;
    use App\Http\Exceptions\MyException;

    //This helps to set CORS sites globally
    $corsSites = require_once config_path("/cors.php");

    foreach ($corsSites as $website) {
        header("Access-Control-Allow-Origin: $website");
    }
    header("Access-Control-Allow-Methods: POST, GET, UPDATE, DELETE, PATCH, HEAD, OPTIONS");

    //This helps to redirect to a route from the base app root
    function redirect($uri) {
        $url = base_path_http($uri);

        header("location:" .$url);
    }

    //Data input security formatter aginst cross site scripting - xss attack
    function filter($data) : string {
        //connect to get a db instance
        $con = Db::dbConnect();

        $data=htmlspecialchars($data);
        $data=stripslashes($data);
        $data=trim($data);
        $data=mysqli_real_escape_string($con,$data);
        return $data;
    }

    //This function helps to check if a user is posting to a from from external site
    //This combats the cross site referer (csrs) scripting
    function checkPOSTINGReferer() {
        if (isset($_SERVER["HTTP_REFERER"])) {
            $http_s = $_SERVER["REQUEST_SCHEME"];
            $serverName = $_SERVER["SERVER_NAME"];
        
            if(!preg_match("/^($http_s:\/\/$serverName){1}[^\'\"]*$/i", $_SERVER['HTTP_REFERER'])) {
               //phpAlert("Posting from another server is not allowed".$_SERVER['HTTP_REFERER']);
               //header("location: ./error_page");
               return 'external_site';
           }
        } else {
            throw new MyException("Posting from another server is not allowed".$_SERVER['HTTP_REFERER']);
            //header("location: ./error_page");
        }
    }

    //This function gets the originating site of a request
    function getOrigin() {
        return $_SERVER["HTTP_ORIGIN"];
    }

    //This function returns true if the HTTP_ORIGIN is same-origin
    function sameOrigin() {
        return $_SERVER["HTTP_SEC_FETCH_SITE"] == "same-origin";
    }

    //This function generates and saves the session csrf token for any form when called
    function csrf_token() {
        if (requestIsAjax()) {
            return null;
        }

        $token = md5(uniqid(mt_rand(), true)); //hex2bin(random_bytes(32));
        //add to the session store

        $_SESSION["csrf_token"] = $token;
        
        return $token;
    }

    //This function sets cookie token for the auth user in the system
    function setAuthCookie($userId) {

        //set server session file and the corresponding http client's session cookie params 
        $_SESSION["user"] = $userId;

        return isset($_SESSION["user"]); 
    }

    //This function revokes the user token and sets the info to null and 0 on db
    function unsetAuthCookie(){
        //then unset the session last
        session_destroy_manager();
    }

    function session_start_manager() {
        //configure the session saving path for all the sessions of this application. Ensure that the storage/session dir exists
        session_save_path(storage_path("/sessions"));
        
        $expiry_time_plus_secs=$GLOBALS["token_expiry_secs"];

        //set these ini configurations before session starts anywhere on the script
        //ini_set("session.name", "PHPsessIdCookieName"); OR
        session_name("PHPsessIdCookieName");

        //session_set_cookie_params(123445566, '/', $_SERVER["HTTP_HOST"], true, true); Option 1 Or assoc array
        session_set_cookie_params(["lifetime" => $expiry_time_plus_secs, "path" => "/", "domain" =>  $_SERVER["HTTP_HOST"], "secure" => true, "httponly" => true, "samesite" => "lax"]);

        session_start();
        //setcookie(session_name(), session_id(), time() + 60*60, "/"); updates the already set session cookie
    }

    function session_destroy_manager() {
        session_unset();
        session_destroy();
    }

    //This function checks the authentication status of the user in cookie session and db token value hence automatic login if true
    function tryAuthUserLogin($userId){
        $authUsernameKey = AuthServiceProvider::AUTH_CHECK_COLUMNS[0];

            if(isAuth() &&  authUser()[0][$authUsernameKey] == $userId){
    
                return true;
            }
        
        return false;
    }

    /** This is the function that controls the redirection of the page authentication state of the user 
    *   call this function in all the pages that requires user authentication especially the users view files
    *   for pages that use session, call it wit null value
    * @param string|null $panel - The name of the panel eg `user` or  `admin`
    */

    function authPanelChecker($panel = null) : void {
        if ($panel != null) {
            if (!in_array($panel, $GLOBALS["ROLE_LIST"])) {
                throw new MyException("Specified panel is not in the global ROLE_LIST");
            }

            $panel == 'user' ? $loginUri = RedirectServiceProvider::USER_LOGIN : $loginUri = RedirectServiceProvider::ADMIN_LOGIN;

            if (!isAuth()) {

                if (requestIsAjax()) {
                    throw new MyException("unAuthenticated");
                }

                redirect($loginUri);

                return;
            }

            $authUserRole = authUser()[0]["role"];
            //check that his role is user role matches the panel he is in
            if ($panel == 'admin' AND $authUserRole == "user") {
                if (requestIsAjax()) {
                    throw new MyException("Admin forbidden from accessing user panel only resource");
                }

                redirect(RedirectServiceProvider::USER_DASHBOARD);
                return;
            }

            if ($panel == 'user' AND  $authUserRole == "admin") {
                if (requestIsAjax()) {
                    throw new MyException("User forbidden from accessing Admin panel only resource");
                }

                redirect(RedirectServiceProvider::ADMIN_DASHBOARD);
                return;
            }
    
            $authUsernameKey = AuthServiceProvider::AUTH_CHECK_COLUMNS[0];

            if (!tryAuthUserLogin(authUser()[0][$authUsernameKey])) {
                //unset the cookie and redirect the user
                unsetAuthCookie();
    
                redirect($loginUri);
            }
        }

    }

    //This function is fired on logout click
    function logout($userId){

        //just delete his tokens if the request is ajax
        if (requestIsAjax()) {
            (new PAToken)->deleteTokens($userId);

            if (isset($_SESSION["user"])) {
                unsetAuthCookie();
            }

            return true;
        }

        //get the role of the user before invalidating his session to help you redirect him
        $role = authUser()[0]["role"];
        
        unsetAuthCookie();

        $role == "admin" ? redirect(RedirectServiceProvider::ADMIN_LOGIN) : redirect(RedirectServiceProvider::USER_LOGIN);
    }
?>