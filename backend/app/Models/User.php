<?php
    namespace App\Models;

    use Utility\BaseModel;
    use App\Providers\AuthServiceProvider;

    class User extends BaseModel {

        protected $table = 'users';

        protected $fillable = [
            AuthServiceProvider::AUTH_CHECK_COLUMNS[0], //username field
            AuthServiceProvider::AUTH_CHECK_COLUMNS[0] == "email" ?: 'email',
            "role",
            AuthServiceProvider::AUTH_CHECK_COLUMNS[1], //password field
            "time_created",
            "date_created",
            //add your more fillables down
        ];

        public $name = 'emma';
    }