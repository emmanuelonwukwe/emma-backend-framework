<?php 
    namespace App\providers;

    class RedirectServiceProvider 
    {
        //This is the user login page link
        public const USER_LOGIN = "/login";

        //This is the user registeration link of the system 
        public const USER_REGISTER = "/register";

        //This is the user redirect link after authentication
        public const USER_DASHBOARD = "/account/dashboard";

       //This is the admin login page link
       public const ADMIN_LOGIN = "/admin/login";

       //This is the admin registeration link of the system 
       public const ADMIN_REGISTER = "/admin/register";

       //This is the admin redirect link after authentication
       public const ADMIN_DASHBOARD = "/admin/dashboard";
    }
    
?>