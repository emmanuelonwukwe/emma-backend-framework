<?php 
    namespace App\Events;

    use App\Providers\MailContentsProvider;

    class SendResetPasswordEmail extends EventsClass {
        /**
         * The subject of the mail
         * @var string
         */
        public $subject;

        /**
         * The receiver email address
         * @var string
         */
        public $emailAddress;

        /**
         * The email body stringified html
         * @var string
         */
        public $body;

        public function  __construct($email, $reset_key){
            $this->subject = MailContentsProvider::resetPasswordMailSubject();
            $this->body = MailContentsProvider::resetPasswordMailBody($reset_key);
            $this->emailAddress = $email;
        }
    }
?>