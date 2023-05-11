<?php
    namespace App\Listeners;

    use PHPMailer\MailerBot;
    use Events\SendResetPasswordEmail;

    class PushResetPasswordEmail extends MailerBot implements ListenersHandle {

        /**
         * This is the handle method the EventsClass::dispatch() calls
         * @param Events\SendResetPasswordEmail $event - This is the event to be handled
         * @return void
         */
        public function handle( $event){
            MailerBot::mailTo($event->subject, $event->body, $event->emailAddress);
        }
    }