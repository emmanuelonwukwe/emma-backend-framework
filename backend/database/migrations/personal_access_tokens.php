
<?php
    use App\Http\Exceptions\QueryException;
    use App\Providers\AuthServiceProvider;

    function personal_access_tokensUp($table = "personal_access_tokens") {
        global $con;

        $authUsernameKey = AuthServiceProvider::AUTH_CHECK_COLUMNS[0];

        //create the transactions users table if not exist
        if(mysqli_query($con,"CREATE TABLE IF NOT EXISTS $table( 
            id INT NOT NULL  AUTO_INCREMENT,
            $authUsernameKey VARCHAR(255) NOT NULL, 
            token VARCHAR(255) NOT NULL,
            default_name VARCHAR(255) NOT NULL,
            valid VARCHAR(255) NOT NULL,
            time_created VARCHAR(255) NOT NULL,  
            date_created VARCHAR(255) NOT NULL, 
            PRIMARY KEY (id))ENGINE=INNODB")
        ){
            $msg="$table table successfully created";        
        }else{
            $msg="$table table not created";
            throw new QueryException($msg.'Mysqli Error: '.mysqli_error($con));
        }
    }

    function personal_access_tokensDown($table = 'personal_access_tokens') {
        global $con;

        mysqli_query($con, "DROP TABLE $table");
    }
?>