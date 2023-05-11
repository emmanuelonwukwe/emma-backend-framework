<?php
    namespace App\Listeners;

    interface ListenersHandle {
        /**
         * The class to be implemented by all listeners to handle the event
         * @param object $event - The event class to be handled
         */
        public function handle($event);
    }