<?php
    namespace App\Models;

    use Utility\BaseModel;

    use App\Http\Exceptions\MyException;
    use App\Providers\AuthServiceProvider;

    class PAToken extends BaseModel
    {

        /**
         * This is the name of the created db table where the personal access tokens are stored
         * @var string
         */
        protected $table="personal_access_tokens";

        private $authUsernameKey = AuthServiceProvider::AUTH_CHECK_COLUMNS[0];

        protected $fillable = [
            AuthServiceProvider::AUTH_CHECK_COLUMNS[0],
            'token',
            'default_name',
            'valid',
            'time_created',
            'date_created',
        ];

        /**
         * This function helps to create token whick will be sent to the user to store on login
         * @return string - The token
         */
        public function createToken($default_name = 'PAToken') {
            if (!isAuth()) {
                throw new MyException("Unauthenticated user cannot create token");
            }

            $authUsername = authUser()[0][$this->authUsernameKey];

            if(!(new User)->exists($authUsername)) {
                throw new MyException("Unexisting user cannot create token");
            }

            $token = self::PATokenGenerate();

            if ($this->exists($authUsername, $this->authUsernameKey)) {
                $this->update([
                    'token' => $token,
                    'default_name' => $default_name,
                    'time_created' => $this->timeNow,
                    'date_created' => $this->dateNow,
                    'valid' => true
                ], $where = "$this->authUsernameKey='$authUsername'");
            } else {
                $this->create([
                    $this->authUsernameKey => $authUsername,
                    'token' => $token,
                    'default_name' => $default_name,
                    'time_created' => $this->timeNow,
                    'date_created' => $this->dateNow,
                    'valid' => true
                ]);
            }

            if (requestIsAjax()) {
                //close his session file so he can only be using token to validate
                unsetAuthCookie();
            }

            return $token;
        }

        /**
         * This function helps to destroy a particular user token. Used specifically on logout
         * @param string $userId - The user whose token is to be destroyed
         * @return string - The token
         */
        public function deleteTokens($userId) {

            if ($this->exists($userId, $this->authUsernameKey)) {
                $this->delete($where = "$this->authUsernameKey='$userId'");
            } 

            return true;
        }
    }
?>