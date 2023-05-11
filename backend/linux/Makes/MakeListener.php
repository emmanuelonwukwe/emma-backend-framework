<?php
    namespace App\Listeners;

    use PHPMailer\MailerBot;
    //use Events\SendLoginEmail;

    class MakeListener extends MailerBot implements ListenersHandle {

        /**
         * This is the handle method the EventsClass::dispatch() calls
         * @param \App\Events\SendLoginEmail $event - This is the event to be handled
         * @return void
         */
        public function handle( $event){
            //Remember: This is the only method that is called with respect to this listener mapped event in the EventsServiceProvider

            //MailerBot::mailTo($event->subject, $event->body, $event->emailAddress);
        }
    }