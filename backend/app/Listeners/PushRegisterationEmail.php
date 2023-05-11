<?php
    namespace App\Listeners;

    use PHPMailer\MailerBot;
    use Events\SendRegistrationEmail;

    class PushRegistrationEmail extends MailerBot implements ListenersHandle {

        /**
         * This is the handle method the EventsClass::dispatch() calls
         * @param Events\SendRegistrationEmail $event - This is the event to be handled
         * @return void
         */
        public function handle($event){
            MailerBot::mailTo($event->subject, $event->body, $event->emailAddress);
        }
    }