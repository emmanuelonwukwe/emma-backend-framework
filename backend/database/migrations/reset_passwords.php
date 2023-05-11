
<?php
    use App\Http\Exceptions\QueryException;
    use App\Providers\AuthServiceProvider;

    function reset_passwordsUp($table = "reset_passwords") {
        global $con;

        //create the transactions users table if not exist
        if(mysqli_query($con,"CREATE TABLE IF NOT EXISTS $table( 
            id INT NOT NULL  AUTO_INCREMENT,
            email VARCHAR(255) NOT NULL, 
            reset_key VARCHAR(255) NOT NULL,
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

    function reset_passwordsDown($table = 'reset_passwords') {
        global $con;

        mysqli_query($con, "DROP TABLE $table");
    }
?>