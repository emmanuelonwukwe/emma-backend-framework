## About backend project
- This helps to quickly work on a system website development or app apis

## Setting up tips to avoid unecessary errors
- configure backend/database/migrations
- configure backend/database/migrations/migrate.php : `$migration_tables`
- configure backend/config/db.php
- configure backend/Utility/Db.php -> setDbConnect() : 'root'
- configure backend/config/globals.php
- configure backend/config/email.php
- configure backend/app/Providers/AuthServiceProvider.php : `AUTH_CHECK_COLUMNS`
- configure backend/app/Models/... : `$fillable`
- configure backend/config/globals.php : `$GLOBALS['DB_TABLES']`
- configure backend/config/globals.php : `$GLOBALS['ROLE_LIST']`
- configure backend/config/globals.php : `$GLOBALS['MODE']` of our app
- you must require_once __DIR__.'/vendor/autoload.php'; in all your view routes[api,pages] after any use import

## Exception tips
- Note: Try catch exception on your controllers else the uncaught errror handler will help out to catch them
- Remeber to configure your Exception handler render function to your taste

## Quick migration table
```php
<?php
    //on a  view route
    require_once __DIR__.'/vendor/autoload.php';

    //migrate the tables to db
    migrateUp();

    //Drops the tables
    migrateDown();
>
```

## Quick Registration
- In the RegisterController, do the following

```php
<?php 
    use Utility\Register;
    use Utility\Auth;

    class RegisterController {
        public static function signup($dataArray) {
        /** 1) Do your POST request data validation checks, NOTE:
         * the checks for unique userId and password set in your App\Providers\AuthServiceProvider has already been made.
         * You must pass your authentication [username, password]  keys 
        *  to be exactly same as it is in your AuthServiceProvider AUTH_COLUMNS_KEYS in the $dataArray.
        *   also, all data are being filtered with filter() helper at the backend b4 its created in db.
        * You do not have to hash the password as it is already hashed at the back
        */

            //2) do the following. $dataArray may be your $_POST or modified of it
            if(Register::create(array<string, string> $dataArray)){
                //session is started and set $_SESSION['user'] at this point
                Auth::login($dataArray[AuthServiceProvider::AUTH_CHECK_COLUMNS[0]]);

                //3) redirect him to a view dashboard with authPanelCheck($panel) helper function as a middleware
                //This authPanelCheck function also starts session on the view route;
            }
        }
        
    }
>
```

- In the view registration form, post the fillable data as in the User model
- Admin Registeration must have this field `ADMIN_REGISTER_KEY` value corresponding to that in the backend/config/globals.php $GLOBALS[`ADMIN_REGISTER_KEY`]
- call `RegisterController::signup(...)` on submit

## Quick Login
- In the LoginController, do the following

```php
<?php 
    use Utility\Auth;

    class LoginController {
        
        public static function authenticate($credentials) {
        /** 1) Do your POST request data validation checks, NOTEa: You must pass your authentication 
        *  [username, password] keys to be exactly same as it is in your AuthServiceProvider AUTH_COLUMNS_KEYS
        */

            //2) do the following. $credentials may be your $_POST or modified of it
            if((new Auth)->attempt(array<string, string> $credentials)){
                //session is started and set $_SESSION['user'] above after attempt is successful

                //3) redirect him to a view dashboard with authPanelCheck($panel) helper function as a middleware
                //This authPanelCheck function also starts session on the view route;
            }
        }
        
    }
>
```

- In the view login file, the fields must follow the authServiceProvider authentication keys [username, password]
- call the `LoginController::authenticate(...)` on submit

## Quick logout
```php
<?php
    use Utility\Auth;
    Auth::logout();
>
```

## Quick account Change AND email Reset password
- In the ResetPasswordController, do the following

```php
<?php 
    use App\Models\ResetPassword;
    use App\Events\SendPasswordResetEmail;
    use App\Events\EventsClass;

    class ResetPasswordController {
        /**
         * This is the function that changes the user account password in the account profile setting page
         * @param array<string, mixed> $dataArray - Must have the following keys ["old_password" =>, "new_password" =>, "confirm_password" =>  ]
        */
        public static function changeAccountPassword(array $dataArray) {
            if((new ResetPassword)->changeAccountPassword($dataArray)){
                //password is changed
            }
        }
        
        public static function startReset($email) {
            /** 1) Do your POST request data validation checks, 
            */
            //2) do the following. $email may be your $_POST or modified of it
            if(($resetPassword = new ResetPassword)->setResetKey($email)){
                //3) get the reset key set and call the email sending event to send the him the link

                $reset_key = $resetPassword->getResetKey($email);
                //eg of the email link the user should receive will direct him to complete reset page onclick is
                //already in the email body provider awaiting the reset_key to complete the link.

                $reset_link_complete = $reset_key;

                EventsClass::dispatch(new SendPasswordResetEmail($email, $reset_link_complete));
            }
        }
        
        /**
         * This is called to complete the reset password once the user has gotten the reset link sent to him
        */
        public static function completeReset($new_password, $confirm_password) {
            $reset_key = $_GET['reset_key'];

            if((new ResetPassword)->completeReset($reset_key, $new_password, $confirm_password)) {
                //reset is completed and password is changed
            }
        }
    }
```
- A. The start password reset form will ask for the user email
- call `ResetPasswordController::startReset(...)` on submit

- B. The complete password reset form will ask the user for his new_password and confirm_password fields
- call `ResetPasswordController::completeReset(...)` on submit

- C. To change account password, `authUser() profile page` form must have these keys exactly [old_password, new_password, confirm_password] in the form field
- call - call `ResetPasswordController::changeAccountPassword(...)` on submit

## Creating data to db model
```php
<?php
    use App\Model\User;
    //return bool
    ($user = new user)->create([
        "fillable_key1" => "value1",
        "fillable_key2" => "value2",
    ]);
>
```

## Retrieving data from db model
```php
<?php
    use App\Model\User;
    //return array<int, array> conditional retrieve
    ($user = new user)->where("email='$email'");

    //or

    //return array<int, array> everything in the model
    $user->all()

    //or with pagination data

    //return array<string, string> with pagination data
    $paginated = $user->paginate(10, $conditionIfNeeded);

    //getting the pagination dataArrayToShow
    //@var array<int, array>
    $data = $paginated["data"];

    //To use pagination on view, add the query "?page=1" to the view route that calls  this model paginate()
>
```

## Updating data to db model
```php
<?php
    use App\Model\User;
    //return bool
    ($user = new user)->update([
        "fillable_key1" => "value1",
        "fillable_key2" => "value2",
    ], $where= "username='$usernameToUpdate'");
>
```

## Deleting data from db model
```php
<?php
    use App\Model\User;
    //return bool
    ($user = new user)->deleete($where= "username='$deleteUsername'");
>
```

## API users

- After login, generate a personal access token, save it on the user device store. Pass it to the header `Token` of the http client you are using and make subsequent requests with it to allow all authentication related functions to work eg authUser(), isAuth(), authPanelChecker($panel) etc;
- `authPanelChecker()` works only to start session on any auth/or unauthenticated routes but passing a panel args to it requies that the user must be auth by session or token to access the panel.

## Csrf management
- call `csrf_token()` method in input field to generate token. Ensure that session is started before the function call else it will not set the session for the token.
- Any controller function that needs csrf verification do `Db[or any Model]::csrf_verify($your_input_field_token)`
- You must use POST method to post the csrf_token

- 1. using only session authentication will use csrf_token field when you call the csrf_verify function above on non-xhr requests but xhr requests of same-origin, token and csrf are redundant.

- 2. using only token authentication will use header `Token`  when you call the csrf_verify function above

- 3. using both session and token header authentication will use header `Token` for cross-site xhrequest when you are verifying csrf and csrf_token when its not xhr

## Setting authentication types
```php
<?php
    //session auth only [call only login] ajax requests of same-origin will work without token
    Auth::login($userId);

    //token auth only [call login then tokenCreate]
    Auth::login($userId);
    (new PAToken)->tokenCreate();

    //token and session auth together [call login, tokenCreate, and login again]
    Auth::login($userId);
    (new PAToken)->tokenCreate();
    Auth::login($userId);
>
```
## Creating and deleting tokens
```php
<?php
    use App\Model\PAToken;
    // You must be authenticated before you can generate token ie after login attempt is suceessful

    //How to generate token preferrably after Auth::login($userId) is success 
    (new PAToken)->createToken();

    //how to delete token preferably on logout $userId is the token user AUTH_CHECK username
    (new PAToken)->deleteTokens($userId);

    //set header `Token:$createdToken` for api user requests
>
```

## Global helper functions
```php
<?php
    require_once __DIR__.'/vendor/autoload.php';

    //usefull global functions are in the backend/helpers functions
    redirect('/filename')// redirects from the root of the project to the filename
    requestIsAjax() //check if request is ajax;
    isAuth(); //checks if session user is set
    authUser() //returns array<int, array> the db row of the authuser
    showMsg(string $msg, int $type) // show message $type 1 = success, 2= error
    phpAlert(string $msg) // alerts
    base_path_http(string $uri) // returns the http://domain/ root of your app or before.../$uri if set used by 
    //redirect() to properly redirect
    base_path($uri) //return the root path of our app .../$uri check backend/helpers/path_finder.php to get more path helpers
    responseJson(array $dataArrayToEncode, $http_status_code = 200) // used mostly if requestIsAjax 
    authPanelChecker($panel = null) // to add session_start to a route and to regulate `user` and `admin` panels respectively
    csrf_token() // used mostly in form fields name=`csrf_token` to set and get csrf token for each form request
    Db::csrf_verify() // all models inherited this function for checking csrf_tokens in controllers it throws error if  the token is invalid
    filter(string $data) // used to filter input fields against xss
    Auth::logout() // call to logout user
    Auth::login($username) // call to login user especially after register. This is called automatically after loing attempt is success.
    Db::dbConnec() // mysqli instance to be used for query. All models inherits this function

>
```
## Middleware management
- The global middleware for regulating the user and admin dashboard can be used in the route or the controller
-  authPanelChecker() : this only starts session on the page used mostly by unauth routes/controllers that need session eg login, registration, reset password, and complete password reset routes

-  authPanelChecker("user") : this starts session on the users page only. Used mostly by user auth routes/controllers to regulate the user panel only.

-  authPanelChecker("admin") : this starts session on the admin page only. Used mostly by admin auth routes/controllers to regulate the admin panel only.

## Image upload functionality
- In your form you can do multiple images eg <input type="file" name="myimage[]" accept=".jpg,.png,.jpeg" multiple>
- in your controller call this function on any model eg User model
```php
<?php
    use App\Models\User;

    if (isset($_FILES['myimage'])) {
        $serverStoreDir = "../assets/property-uploads"; //The location on the server where the image will be store on your server. Ensure that this location exists else the file will not upload.

        $UserImageUploader = new User;
        //configure the uploader settings
        $UserImageUploader->setAllowedFileTypes([".jpg", "pdf", "png"]); // can only upload this type of files
        $UserImageUploader->setAllowedFileMaxSize(2.5); //eg 2.5mb maximum can upload with this code

            //uploads the file to the server $serverStoreDir. Ensure that this directory exists else the image will not upload. returns true if uploaded
        $UserImageUploader->uploadFile($serverStoreDir, "myimage");

        //get the system generated link to be stored on db for the file location on the server
        echo $UserImageUploader->getFilePath($serverStoreDir, "myimage");
    }
>
```
## Events and listeners
- Example of how to fire event here is shown below

```php
<?php
    use App\Events\SendPasswordResetEmail;
    use App\Events\SendLoginEmail;
    use App\Events\EventsClass;

    //fire login email event
    EventClass::dispatch(new SendLoginEmail);

    //sending reset password email
    EventsClass::dispatch(new SendPasswordResetEmail($email, $reset_key));
>
```

## Using emma_backend_ ...commands to fasten your new projects (First(One) time setting up)
- 1 - Ensure to clone emma-backend-framework in you htdocs
- 2 - Open your git bash (shell) and type this command ```cd emma-backend-framework``` then
- 3 - Run this command ```bash  emma_command_setup.bash``` then
- 4 - Run this command ```source  $HOME/.bashrc```
- 5 - Now you have successfully installed and set up emma_backend_ ...commands on your system, navigate out of here to your project root as all emma_backend commands must be done on any of your projects root. 

##### List of emma_backend_ ...commands and their functions
- On your project root just run this command ```emma_backend_add`` then hit enter key. Congrats, emma_backend files are installed in your project. 
- All emma_backend command start with emma_backend_ followed by the command
- All emma_backend command must be executed on the project root directory.
- Tip: To quickly see all these commands on your shell, type `emma_backend` then double tap your tab key

```sh
# 1 - install emma backend automatically to a new project root
emma_backend_add

# 2 - removes emma backend automatically from a project root
emma_backend_remove

# 3 - starts php inbuilt server on port[80 or 8000 or 8080] in the project root
emma_backend_serve80
emma_backend_serve8000
emma_backend_serve8080

#4 - making classes
emma_backend_make_controller
emma_backend_make_model
emma_backend_make_event
emma_backend_make_exception
emma_backend_make_listener
emma_backend_make_provider
emma_backend_make_utility
emma_backend_make_migration

```

