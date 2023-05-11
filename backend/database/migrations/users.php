

<?php
    use App\Http\Exceptions\QueryException;
    use App\Providers\AuthServiceProvider;

    function usersUp($table = "users") {
        global $con;
        
        $authUsernameKey = AuthServiceProvider::AUTH_CHECK_COLUMNS[0];
        $authPasswordKey = AuthServiceProvider::AUTH_CHECK_COLUMNS[1];

        if ($authUsernameKey !== 'email') {
            $addAuthUsernameColumn = "$authUsernameKey VARCHAR(255) NOT NULL,";
        } else {
            $addAuthUsernameColumn = '';
        }

        //create the transactions users table if not exist
        if(mysqli_query($con,"CREATE TABLE IF NOT EXISTS $table( 
            id INT NOT NULL  AUTO_INCREMENT,
            $addAuthUsernameColumn
            email VARCHAR(255) NOT NULL,
            role VARCHAR(255) NOT NULL,
            $authPasswordKey VARCHAR(255) NOT NULL, 
            time_created VARCHAR(255) NOT NULL, 
            date_created VARCHAR(255) NOT NULL, 

            /**add your more fillable fields down */

            /*$currencyBuff*/
            PRIMARY KEY (id))ENGINE=INNODB")
        ){
            $msg="$table table successfully created";        
        }else{
            $msg="$table table not created";
            throw new QueryException($msg.'Mysqli Error: '.mysqli_error($con));
        }
    }

    function usersDown($table = 'users') {
        global $con;

        mysqli_query($con, "DROP TABLE $table");
    }
?>