<?php
    namespace App\Providers;

    class AuthServiceProvider {
        /**
         * The columns to be used for authenticating a user in the users db table. The first item serves as the userId column
         * while the second serves as the password column name
         * @var string
         */
        public const AUTH_CHECK_COLUMNS = ['email', 'password'];

    }