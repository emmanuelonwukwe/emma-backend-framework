<?php
    use App\Models\User;
    use App\Models\PAToken;
    use App\Utility\Auth;
    use App\Providers\AuthServiceProvider;
    use App\Http\Exceptions\MyException;

    //This returns the user data if the token set is real, if header token is not found, it throw error 
    function findUserBySetHeaderToken(){
        $authUsernameKey = AuthServiceProvider::AUTH_CHECK_COLUMNS[0];

        //check if the xhr request is from same-origin or its cross-site request
        if (sameOrigin()) {
            if (isset($_SESSION["user"])) {

                return (new User)->where("$authUsernameKey='$_SESSION[user]'");
            }
            //if the user is not in session check down if he has a token key to check with.
        }

        if (isset(apache_request_headers()['Token'])) {
            $headerToken = apache_request_headers()['Token'];

            if (!(new PAToken)->exists($headerToken, 'token')) {
                throw new MyException("Invalid header `Token`");
            }

            //get the username of the token owner
            $tokenUsername = (new PAToken)->get($authUsernameKey, "token='$headerToken'");

            if ($tokenUsername != null) {

                return (new User)->where("$authUsernameKey='$tokenUsername'");
            }
        } else {
            throw new MyException("Unauthenticated XHR request must supply header token");
            //Developer help: All XMLHttpRequest must supply the header `Token`, to call authUser methods after tokenCreate is called. Try Auth::login | logout[without csrf verify] after calling tokenCreate if your company is website.
        }
    }

    /**
     * This checks if the user is authenticated or not
     * @return bool
     */
    function isAuth() {
        //This allow token generation to check if he is authenticated at the point of creating the token. If he is
        //an ajax requester, this session is automatically destroyed after token is created. To allow subsequent
        //requests to be made with tokens
        if (isset($_SESSION["user"])) {
            return true;
        }

        //check if its ajax request
        if (requestIsAjax()) {
            return findUserBySetHeaderToken();
        }
        
        return isset($_SESSION["user"]);
    }

    /**
     * This function helps to get the authenticated user else it returns null
     * 
     * @return array<int, array>|null
     */
    function authUser() {
        $authUsernameKey = AuthServiceProvider::AUTH_CHECK_COLUMNS[0];

        //This allow token generation to check if he is authenticated at the point of creating the token. If he is
        //an ajax requester, this session is automatically destroyed after token is created. To allow subsequent
        //requests to be made with tokens
        if (isset($_SESSION["user"])) {

            return (new User)->where("$authUsernameKey='$_SESSION[user]'");
        }

        //check if its ajax request
        if (requestIsAjax()) { 
            return findUserBySetHeaderToken();
        }

        return null;
    }