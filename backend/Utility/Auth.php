<?php
    namespace Utility;

    use App\Models\PAToken;
    use App\Providers\AuthServiceProvider;
    use App\Http\Exceptions\MyException;
    use App\Http\Exceptions\QueryException;


    class Auth extends Db {

        /**
         * This is the name of the created db table where all the login Authentication is verified from
         * @var string
         */
        protected $table="users";

        /**
         * The names of the db table dbcolumns to which data will be checked
         * Your form input name attributes must also take these fillable names
         * @var array
         */
        protected static $dbColumns = AuthServiceProvider::AUTH_CHECK_COLUMNS;

        /**
         * This function checks the login details and logs in if right with the user db info
         * @param array $credentials - The username and password posted as in the Auth service provider
         * @return bool - true means that it is logged in successfully
         */
        public function attempt(array $credentials) : bool {

            //check the required form fields in the post array
            foreach (self::$dbColumns as $_ => $column_key) {
                if (!array_key_exists($column_key, $credentials) OR $credentials[$column_key] == null) {
                    
                    throw new MyException("The input field: ".strtoupper($column_key). " must not be empty");
                }
            }

           
            //get the corresponding post values for each db column name
            $values=[];
            foreach (self::$dbColumns as $colname) {
                if (array_key_exists($colname, $credentials)) {
                    //hash the password key
                    if ($colname == self::$dbColumns[1]) {
                        $credentials[self::$dbColumns[1]] = hash_hmac("sha256", $credentials[self::$dbColumns[0]] , $credentials[self::$dbColumns[1]]);
                    }

                    $add = filter($credentials[$colname]);
                }
                else{
                    //skip this its not in the array of auth chcking column
                    continue;
                }

                $values[] = $add;
            }

            //check the values in db. If true return true else return false
            $userId = $credentials[self::$dbColumns[0]];
            $password = $credentials[self::$dbColumns[1]];

            if($sql=mysqli_query(parent::dbConnect(),"SELECT * FROM $this->table WHERE ". self::$dbColumns[0]."='$userId' && ".self::$dbColumns[1]."='$password'")){
                if ($r = mysqli_fetch_assoc($sql) > 0) {

                    $this->login($userId);
                    return true;
                }
                else{
                   return false; 
                }
                
            }
            else{
                throw new QueryException("query error occured on Users ","DB_CHECK_ERROR");
            }

        }

        //This sets the session state of the user we want to login
        public static function login($userId) {
            //open session to enable ajax call create token which is going to be closed by PAToken::createToken() automatically
            //if it is ajax call.

            setAuthCookie($userId);
        }

        /**
         * This function also logs out the logged in user out of the system 
         */
        public static function logout() {
            if (isAuth()) {
                logout(authUser()[0][static::$dbColumns[0]]);
            }
        }
    }
    