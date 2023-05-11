<?php
    namespace Utility;
    
    use App\Providers\AuthServiceProvider;
    use App\Http\Exceptions\QueryException;
    use App\Http\Exceptions\CsrfException;

    class Db {
        public function __construct() {
            $this->initSet();
        }

        /**
         * Sets the dbConnect property of the class
         * 
         */
        public static function dbConnect(){
            $dbConfigArray = require config_path('/db.php');
            $host = $dbConfigArray["host"];
            $server  = "root";//$dbConfigArray["server"];
            $password = $dbConfigArray["password"];
            $database = $dbConfigArray["database"];

            //create the database
            $mysqlicon=mysqli_connect($host, $server, $password);
            mysqli_query($mysqlicon, "CREATE DATABASE $database");
            
            //connect to the database
            $mysqlicon=mysqli_connect($host, "root", $password, $database);
            //check connection
            if(!$mysqlicon) {
                die('SERVER HOST Connection Failed'. mysqli_error());
            }
            
            return $mysqlicon;
        }

        /**
         * This function sets the default time as now
         * @return void
         */
        public function timeNow(){
            $this->timeNow = time();
        }

        /**
         * This function sets the userId as the session user
         * @return void
         */
        public function dateNow(){
            $this->dateNow = date("d-m-Y h:i:s");
        }
        
        /**
         * The initial data setting for the class
         * Call this for every function that has to do with userId usage in this class
         * @return void;
         */
        public function initSet(){
            //set the default values when not set by the user;
            !isset($this->dateNow) ? $this->dateNow() : "";
            !isset($this->timeNow) ? $this->timeNow() : "";
        }

        /**
         * This function gets and the return the array of the dbtables 
         * @return array<string, array>
         */
        public static function getDBTables(){
            return $GLOBALS["DB_TABLES"];
        }

        /**
         * This function gets and the return the array of the dbtables 
         * @return array<string, array>
         */
        public static function getSystemStatuses(){
            return $GLOBALS["statuses"];
        }

        /**
         * This function checks if the model exists in the table
         * @param mixed $search_value - The value of the model to be searched for existence on the database
         * @param string $table - The name of the db table where the model will be retrieved from
         * @param string $col_key - The name of the column we are searching the model with
         * @return boolean - true means that the user exists
         */
        public static function modelExists($search_value, $table = 'users', $col_key=  AuthServiceProvider::AUTH_CHECK_COLUMNS[0]){
            //Ensure to space the WHERE b4 the Variable else it query looks like WHEREuserid=
            $sql="SELECT * FROM $table WHERE ". $col_key."='$search_value'";
            if ($qry=mysqli_query(self::dbConnect(), $sql)) {
                return mysqli_num_rows($qry) > 0 ? true : false;
            }
            else {
                throw new QueryException('unable to execute query');
            }
        }

        /**
         * This function returns the selected column of the table
         * @var string $tbname - The name of the table from which column value is to be gotten from
         * @var string $where_set - The WHERE clause setting of the query
         * @var string $get_col - The name of the db column to be returned from the query
         * @return string
         */
        public static function getFromTb($tbname, $get_col, $where_set) : string {
            if ($sql = mysqli_query(self::dbConnect(), "SELECT $get_col FROM $tbname WHERE $where_set")) {
                if ($ar = mysqli_fetch_assoc($sql)) {
                    $get = $ar[$get_col];

                    return $get;
                } else {
                    throw new QueryException("Not able to fetch");
                }

            } else {
                throw new QueryException("Unable to execute query");
            }
        }

        /**
         * This function updates the given table with the $settings and the $where settings
         * @var string $table - The name of the db table to be updated
         * @var string $settings - The SET settings to be used
         * @var string $where_set - The WHERE settings
         * @return bool
         */
        public static function updateTb($table, $settings, $where_set) {
            if($sql=mysqli_query(self::dbConnect(),"UPDATE $table SET $settings WHERE $where_set")){

                return true;
            }
            else{
                throw new QueryException("Could Not update $table table","DB_INSERT_ERROR");
            }
        }

        /**
         * This function credits the users table the amount
         * @var string $table - The name of the table to be credited
         * @var string $amount - The amount to be credited 
         * @var string $col - The name of the column to be credited
         * @var string $where_set -The WHERE clause settings of the query
         * @return bool;
         */
        public static function creditTb($table, $amount, $col, $where_set){
            $col = strtolower($col);
            if ($sql = mysqli_query(self::dbConnect(), "SELECT $col FROM $table WHERE $where_set")) {
                if ($ar = mysqli_fetch_assoc($sql)) {
                    $current_value = $ar[$col];

                    //Then update the table now
                    return self::updateTb($table, $settings="$col=$current_value + $amount", $where_set);
                } else {
                    throw new QueryException("Not able to fetch array");
                }

            } else {
                throw new QueryException("Unable to execute query");
            }
        }

        /**
         * This function credits the users table the amount
         * @var string $table - The name of the table to be debited
         * @var string $amount - The amount to be debited 
         * @var string $col - The name of the column to be debited
         * @var string $where_set -The WHERE clause settings of the query
         * @return bool;
         */
        public static function debitTb($table, $amount, $col, $where_set){
            $col = strtolower($col);
            if ($sql = mysqli_query(self::dbConnect(), "SELECT $col FROM $table WHERE $where_set")) {
                if ($ar = mysqli_fetch_assoc($sql)) {
                    $current_value = $ar[$col];

                    //Then update the table now
                    return updateTb($table, $settings="$col=$current_value - $amount", $where_set);
                } else {
                    throw new QueryException("Not able to fetch array");
                }

            } else {
                throw new QueryException("Unable to execute query");
            }
        }

        /**
         * This function helps to generate transref for the system 
         * @return string
         */
        public static function generateTransref() : string {
            return str_shuffle("ABCDORIH187940").mt_rand(1000,9999);
        }

        /**
         * This function helps to generate password reset key for the system 
         * @return string
         */
        public static function generatePasswordResetKey() : string {
            return str_shuffle("ABCDORIH187940_83787jsijihepweouueou____00001234568780700").mt_rand(1000,9999);
        }

        /**
         * This function helps to generate password reset key for the system 
         * @return string
         */
        public static function PATokenGenerate() : string {
            return str_shuffle("ABCDORIH187940_83787jsi".mt_rand(100000,999999)."jihepweouueou____00001234568780700");
        }

        /**
         * This function is used to verify that the csrf is right
         * @param string $input_token - The token user sent from the form to be verified. Note: this is redundant if
         * ajax request sets header token, or session and token authentications are both in use.
         * @return bool - returns true if the token is right with that on the session store
         */
        public static function csrf_verify($input_token = 'tentative') {
            
            //check that every cross-site ajax request has header token
            if (requestIsAjax()) {
                if (isset($_SESSION["user"])) {
                    findUserBySetHeaderToken();
                }

                //if its not same-origin check header token
                if (!sameOrigin()) {
                    findUserBySetHeaderToken();
                }

                //else its verified to be same origin hence csrf or header token checks is redundant
                return true;
            }
            
            if(!isset($_SESSION["csrf_token"])) {
                throw new CsrfException("csrf_token is not yet set on the session store");
            }

            if($input_token != $_SESSION["csrf_token"]){
                throw new CsrfException("Invalid form token _POSTED. Reload the form page now.");
            }

            return true;
        }
    }