<?php
    namespace Utility;

    use App\Models\User;
    use App\Providers\AuthServiceProvider;
    use App\Http\Exceptions\MyException;

    class Register {
        private static $authColumns = AuthServiceProvider::AUTH_CHECK_COLUMNS;

        /**
         * This function is for the create a new user. It is safer to create a new user with this other than
         * the normal User model create. Because of password hashing and role checkings
         * @return bool - true means that it is inserted successfully to db
         */
        public static function create(array $dataArray): bool {

            if (!isset($dataArray['role'])) {
                throw new MyException("`role` field is required");
            }

            //check that the authentication fields are not empty
            if (!isset($dataArray[self::$authColumns[0]])) {
                throw new MyException("Authentication field `[".self::$authColumns[0]. "]` is required.");
            }

            if (!isset($dataArray[self::$authColumns[1]])) {
                throw new MyException("Authentication field `[". self::$authColumns[1]. "]` is required.");
            }

            if (empty($dataArray[self::$authColumns[0]])) {
                throw new MyException("Authentication fields `[". self::$authColumns[0]. "]` cannot be empty.");
            }

            if (empty($dataArray[self::$authColumns[1]])) {
                throw new MyException("Authentication fields `[". self::$authColumns[1]. "]` cannot be empty.");
            }
            
            foreach ($dataArray as $col => $_) {
                //check the role posted
                if ($col == "role") {
                    if (!in_array($dataArray['role'], $GLOBALS["ROLE_LIST"])) {
                        throw new MyException("Specicied role is not in the global ROLE_LIST");
                    }

                    if ($dataArray["role"] == "admin") {
                        //check the admin registeration key status
                        if (array_key_exists("ADMIN_REGISTER_KEY", $dataArray)) {
                            if ($dataArray["ADMIN_REGISTER_KEY"] != $GLOBALS["ADMIN_REGISTER_KEY"]) {
                                throw new MyException("ADMIN_REGISTER_KEY posted does not match system global ADMIN_REGISTER_KEY");  
                            }
                        } else {
                            throw new MyException("ADMIN_REGISTER_KEY not posted from the admin registration form");  
                        }
                    }

                    //unset the key so that it will not be included for user create
                    unset($dataArray["ADMIN_REGISTER_KEY"]);
                }

                //hash the password key
                if ($col == self::$authColumns[1]) {
                    $dataArray[self::$authColumns[1]] = hash_hmac("sha256", $dataArray[self::$authColumns[0]] , $dataArray[self::$authColumns[1]]);
                }
              
            }


            if (($user = new User)->exists($dataArray[self::$authColumns[0]])) {
                throw new MyException("`".self::$authColumns[0]."` Authentication unique data already exists");
            }

            //insert the values to db
            return $user->create($dataArray);
        }
    }