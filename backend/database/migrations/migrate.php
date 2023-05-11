<?php
    use Utility\Db;
    
    //connect to mysqli server
    $con = Db::dbConnect();

    //ensure to register all your db table names here. else it will not migrateUp or down
    $migration_tables = ['users', 'personal_access_tokens', 'reset_passwords', ];

    foreach ($migration_tables as $table_name) {
        require_once __DIR__."/$table_name.php";
    }

    //call this function to migrate all db tables
    function migrateUp() {
        global $migration_tables;

        //call the tablename up function for each of the tables
        foreach ($migration_tables as $table_name) {
            $callable = $table_name.'Up';

            $callable();
        }

        phpAlert('success all database tables migrated successfully');
    }

    //call this to drop all your db tables
    function migrateDown() {
        global $migration_tables;

        //call the tablename Down function for each of the tables
        foreach ($migration_tables as $table_name) {
            $callable = $table_name.'Down';

            $callable();
        }

        phpAlert('Hey! all database tables dropped successfully');
    }

?>