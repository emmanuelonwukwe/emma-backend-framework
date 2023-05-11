<?php
    namespace App\Http\Controllers;

    use App\Models\ResetPassword;
    use App\Events\SendPasswordResetEmail;

    class ResetPasswordController extends Controller{
        /**
         * This is the function that changes the user account password in the account profile setting page
         * @param array<string, mixed> $dataArray - Must have the following keys ["old_password" =>, "new_password" =>, "confirm_password" =>  ]
        */
        public static function changeAccountPassword(array $dataArray) {
            if((new ResetPassword)->changeAccountPassword($dataArray)){
                //password is 
                
                if (requestIsAjax()) {
                    echo responseJson([
                        "status" => 'success',
                        "message" => 'Account password changed successfully.',
                    ], 200);
                    return;
                }

                //do something if not ajax request
                echo 'password changed success';
            }
        }
        
        public static function startReset($email) {
            /** 1) Do your POST request data validation checks, 
            */
            //2) do the following. $email may be your $_POST or modified of it
            if(($resetPassword = new ResetPassword)->setResetKey($email)){
                //3) get the reset key set and call the email sending event to send the him the link of your complete reset page with the reset key

                $reset_key = $resetPassword->getResetKey($email);
                //eg of the email link the user should receive will direct him to complete reset page onclick

                $reset_link = "https://domain/complete_reset_page.php?reset_key=$reset_key";

                new SendPasswordResetEmail($email, $reset_link);


                echo "link sent";
                return;
            }

            if (requestIsAjax()) {
                echo responseJson([
                    "status" => 'error',
                    "message" => 'Internal unknown server error occured as reset link could not be set at the moment',
                ], 500);
                return;
            }

            //do something if not ajax
            echo 'unknown error occured';
        }
        
        /**
         * This is called to complete the reset password once the user has gotten the reset link sent to him
        */
        public static function completeReset($new_password, $confirm_password) {
            $reset_key = $_GET['reset_key'];

            if((new ResetPassword)->completeReset($reset_key, $new_password, $confirm_password)) {
                //reset is completed and password is changed
                if (requestIsAjax()) {
                    echo responseJson([
                        "status" => 'success',
                        "message" => 'Password reseted successfully.',
                    ], 200);
                    return;
                }

                //if request is not ajax
                echo "reset completed";
            }
        }
    }
