<?php
    /**
     * This typically returns the database configuration of this system to the server
     */
    return [
        'host' => 'localhost',
        'server' => 'if value is "root" then, Hardcode it at Utility/Db.php : DB::setDbConnect() else it will not work',
        'password' => '',
        'database' => 'dbname'
    ];