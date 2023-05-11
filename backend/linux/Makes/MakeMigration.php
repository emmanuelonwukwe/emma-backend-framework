
<?php
    use App\Http\Exceptions\QueryException;
    use App\Providers\AuthServiceProvider;

    //Warning: Do not change this function name or its parameter
    function MakeMigrationUp($table = "MakeMigration") {
        global $con;

        //create the MakeMigration users table if not exist
        //Only Write your table column_name1 ... and their structures
        //make sure to registe this tablename [MakeMigration] in migration.php $db_tables array
        if(mysqli_query($con,"CREATE TABLE IF NOT EXISTS $table( 
            id INT NOT NULL  AUTO_INCREMENT,
            column_name1 VARCHAR(255) NOT NULL, 
            column_name2 VARCHAR(255) NOT NULL,

            /**Leave the time and date created columns*/
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

    //Warning: Do not change this function name or its parameter
    function MakeMigrationDown($table = 'MakeMigration') {
        global $con;

        mysqli_query($con, "DROP TABLE $table");
    }
?>