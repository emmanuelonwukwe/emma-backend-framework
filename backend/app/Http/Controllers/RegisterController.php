<?php
    namespace App\Http\Controllers;

    use App\Models\User;
    use App\Models\PAToken;
    use Utility\Auth;
    use Utility\Register;

    class RegisterController extends Controller{

        public static function signUp() {
            User::csrf_verify( $_POST["csrf_token"] ?? null);
        
            //user registration example
            $isRegistered = Register::create([
                'email' => $_POST["email"] ?? null,
                'password' => $_POST["password"] ?? null,
                'role' => 'user',
                "csrf_token" => $_POST["csrf_token"] ?? null //redundant in ajax call
            ]);
        
            //admim registration example
            // $isRegistered = Register::create([
            //     'email' => $_POST["email"] ?? null,
            //     'password' => $_POST["password"] ?? null,
            //     'role' => 'admin',
            //     "ADMIN_REGISTER_KEY" => $_POST["ADMIN_REGISTER_KEY"] ?? null,
            //     "csrf_token" => $_POST["csrf_token"] ?? null
            // ]);
        
            if ($isRegistered) {
                //start the user session cookie so u can call the authUser() methods in createToken function
                Auth::login($_POST["email"]);
                
                $authUser = authUser()[0];
        
                //stop here do not create token if origin is same-origin as stateless session based auth will be in
                //csrf tokens are not necessary for xhr requests at this point but needed on page form request
                //csrf_verify does not affect any xhr at this point and no header token is needed. cross-sites requests at this point must provide header token before their requests could be validated

                //create the header token u will be using for cross-site xmlhttp requests as csrf is not verified with ajax requests.
                ////$token = (new PAToken)->createToken();
    
                //stop at this point after token is created for apps as the function destroys the session in order to only
                //authenticate users with the created token you will have to store on their device local store.
    
                ////Auth::login($_POST["email"]); // If it's website, login again so you can be using both token to 
                //send cross-site ajax requests safely and system uses session for auth methods and csrf verification of any non ajax post request. system uses session for auth methods and csrf verification of any non ajax post request.
        
                if (requestIsAjax()) {
                    echo responseJson([
                        "status" => 'success',
                        "message" => 'Account successfully created.',
                        "Token" => $token,
                        "user" => $authUser
                    ], 200);
                    return;
                }
            
                //redirect to dashboard
                echo "registered success";
                return;
            }
        
            echo responseJson([
                "status" => 'error',
                "message" => 'Unknown server error occured.',
            ], 500);
            return;
        }
    }
