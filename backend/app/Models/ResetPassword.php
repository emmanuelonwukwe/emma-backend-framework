<?php
    namespace App\Models;

    use Utility\BaseModel;

    use App\Http\Exceptions\MyException;
    use App\Providers\AuthServiceProvider;

    class ResetPassword extends BaseModel
    {

        /**
         * This is the name of the created db table where the password reset keys are stored
         * @var string
         */
        protected $table="reset_passwords";

        protected $fillable = [
            'email',
            'reset_key',
            'time_created',
            'date_created'
        ];

        /**
         * This is the users table where data will be updated for the reset
         * @var string
         */
        protected $users_table = "users";

        /**
         * This function is called by the auth user to change his password mostly in his profile settings
         * 
         */
        public function changeAccountPassword(array $dataArray) {
            $authUsernameKey = AuthServiceProvider::AUTH_CHECK_COLUMNS[0];
            $authPasswordKey = AuthServiceProvider::AUTH_CHECK_COLUMNS[1];
            $userId = authUser()[0][$authUsernameKey];

            if (!array_key_exists("confirm_password", $dataArray) || !array_key_exists("old_password", $dataArray)) {
                throw new MyException("old_password and confirm_password fields must be supplied");
            }

            //check if the new and confirm password match
            if ($dataArray["new_password"] != $dataArray["confirm_password"]) {
                throw new MyException("New Password and Confirm Password did not match.");
            }

            //check if the old password matches db password
            $dataArray["old_password"] = hash_hmac("sha256", $userId, $dataArray["old_password"]);
            if ($dataArray["old_password"] != static::getFromTb($this->users_table, $get_col="$authPasswordKey", $where_set="$authUsernameKey='$userId'")) {
                throw new MyException("Old password did not match your login password.");
                
            }

            if (empty($dataArray["old_password"]) || empty($dataArray["new_password"]) || empty($dataArray["confirm_password"])) {
                throw new MyException("Old Password, New password and Confirm password must not be empty");
            }

            //hash the password
            $currentPassword=hash_hmac("sha256", $userId, $dataArray["new_password"]);
            $key = $authPasswordKey;
            $settings="$key='$currentPassword'";

            //update the password
           return parent::updateTb($this->users_table, $settings, $where_set = "$authUsernameKey='$userId'");
        }

        /**
         * This function checks the user email in order to create the reset link to be given to the user
         * @param string $email - The email of the user to which the mail will be sent to
         * @param string $reset_key - The reset key to be sent to the user
         * @return bool
         */
        public function setResetKey($email) : bool{
            $email = filter($email);
            $reset_key = filter(parent::generatePasswordResetKey());
            $this->time = $this->timeNow;

            if (parent::modelExists($email, $this->users_table, 'email')) {
                if ($this->exists($email, 'email')) {
                    //update the resetting key
                    parent::updateTb($this->table, $settings = "reset_key='$reset_key', time_created=$this->time,  date_created='$this->dateNow'", $where_set = "email='$email'");

                    return true;
                } else {
                    //insert to the table the resetting key
                    if ($this->create(['email' => $email, "reset_key" => $reset_key, "time_created" => $this->time, 'date_created' => $this->dateNow])) {
                        
                        return true;
                    } else {
                        throw new MyException("Unable to execute query");
                    }
                }
    
                
                
            } else {
                throw new MyException("Email does not match any record");
            }
        }

        /**
         * This function gets the reset link the user has on the password_reset table
         * @return string - The reset key of the email
         */
        public function getResetKey($email) : string {
            if ($this->exists($email, 'email')) {
                return $this->get("reset_key", "email='$email'");
            } 

            throw new MyException("Resetkey Not set before getting");
            
        }


        /**
         * This function updates the user password  in the users table and deletes the reset key from the table
         * It is called at the complete reset password stage where the user have to create new password with the 
         * valid email link sent to him
         * @param string $reset_key - The reset_key the user sets on the reset url query string
         * @param string $new_password - The new password the user wants to change to 
         * @param string $confirm_password - The confirm new password
         * @return bool
         */
        public function completeReset($reset_key, $new_password, $confirm_password) : bool {
            $reset_key = filter($reset_key);
            $new_password = filter($new_password);
            $confirm_password = filter($confirm_password);
            
            //check if the reset key matches any reset key on the table
            if (!$this->exists($reset_key, 'reset_key')) {
                throw new MyException("Invalid resetting link retry new password reset.");
            }

            //check if the time of the key validity has not expired 5 minutes
            $time = parent::getFromTb($this->table, $get_col="time_created", $where_set="reset_key='$reset_key'");

            $timeDifSec = time() - $time;
            if($timeDifSec > 60*5) {
                throw new MyException("This reset link has expired. Try new reset password process");
            }

            if (empty($new_password) || empty($confirm_password)) {
                throw new MyException("New password and Confirm password must not be empty");
            }

            //check if the new and confirm password match
            if ($new_password != $confirm_password) {
                throw new MyException("New Password and Confirm Password did not match.");
            }

            $email = parent::getFromTb($this->table, $get_col="email", $where_set="reset_key='$reset_key'");
            $password = hash_hmac("sha256", $email, $new_password);

            parent::updateTb($this->users_table, $settings = AuthServiceProvider::AUTH_CHECK_COLUMNS[1]."='$password'", $where_set="email='$email'");

            //delete the key from the table
            if($this->delete("reset_key='$reset_key'")){
                //echo deleted
            }

            return true;
        }

    }
?>