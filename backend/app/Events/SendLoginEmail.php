<?php 
    namespace App\Events;

    use App\Providers\MailContentsProvider;

    class SendLoginEmail extends EventsClass {
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

        public function  __construct(){
            $this->subject = MailContentsProvider::loginMailSubject();
            $this->body = MailContentsProvider::loginMailBody();
            $this->emailAddress = authUser()[0]["email"];
        }
    }
?>