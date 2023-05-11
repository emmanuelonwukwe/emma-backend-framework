<?php
    namespace App\Http\Controllers;

    use App\Models\User;


    class UserController extends Controller{

        /**
         * This returns the user data of the authenticated user
         */
        public static function data() {
            User::csrf_verify($_POST['csrf_token'] ?? null); //cross-sites restriction on web
            
            $authUser = authUser()[0];

            if (requestIsAjax()) {
                echo responseJson([
                    "status" => 'success',
                    "message" => 'Authenticated user Details',
                    "user" => $authUser
                ], 200);
                return;
            }

            //request is not ajax
            var_dump($authUser);
        }
    }
