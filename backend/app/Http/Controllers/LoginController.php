<?php
    namespace App\Http\Controllers;

    use App\Models\User;
    use App\Models\PAToken;
    use Utility\Auth;
    use Utility\Register;

    class LoginController extends Controller {

        public static function authenticate() {
            User::csrf_verify( $_POST["csrf_token"] ?? null);

            //user registration example
            $credentials = [
                'email' => $_POST["email"] ?? null,
                'password' => $_POST["password"] ?? null
            ];
    
            // $UserImageUploader = new User;
            // //configure the uploader settings
            // $UserImageUploader->setAllowedFileTypes([".jpg", "pdf", "png"]); // can only upload this type of files
            // $UserImageUploader->setAllowedFileSize(2); // 2mb

            // //image uploads 
            // $UserImageUploader->uploadFile($serverStoreDir = "assets", "myimage");

            // //get the link to be stored on db for the image location on the server
            // echo $UserImageUploader->getFilePath($serverStoreDir, "myimage");

            if ((new Auth)->attempt($credentials)) {
                //user session cookie is automatically started so u can safely call the authUser() methods in createToken function;
                
                $authUser = authUser()[0];
                //stop here do not create token if origin is same-origin as stateless session based auth will be in
                //csrf tokens are not necessary for xhr requests at this point but needed on page form request
                //csrf_verify does not affect any xhr at this point and no header token is needed. cross-sites requests at this point must provide header token before their requests could be validated

                //create the header token u will be using for cross-site xmlhttp requests as csrf is not verified with ajax requests.
                /////$token = (new PAToken)->createToken();
    
                //stop at this point after token is created for apps as the function destroys the session in order to only
                //authenticate users with the created token you will have to store on their device local store.
    
                /////Auth::login($_POST["email"]); // If it's website, login again so you can be using both token to 
                //send cross-site ajax requests safely and system uses session for auth methods and csrf verification of any non ajax post request.
    
                if (requestIsAjax()) {
                    echo responseJson([
                        "status" => 'success',
                        "message" => 'Login success.',
                        "Token" => $token,
                        "user" => $authUser
                    ], 200);
                    return;
                }
               
                //redirect to dashboard
                echo 'login success'; 
                return;
            }
    
            echo responseJson([
                "error" => 'error',
                "message" => 'Invalid email or password.',
            ], 403);
            return;
        }

        /**
         * The logout function
         */
        public static function logout() {
            User::csrf_verify( $_POST["csrf_token"] ?? null);

            Auth::logout();

            if (requestIsAjax()) {          
                echo responseJson([
                    "status" => 'success',
                    "message" => "logged out successfully",
                ], 201);
                
                return;
            }

            //do something if not ajax
            echo 'logged out';
        }
    }
