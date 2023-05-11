<?php
    namespace App\Events;

    use App\Http\Exceptions\MyException;
    use App\Providers\EventsServiceProvider;

    abstract class EventsClass
    {
        /**
         * This is the function that dispaches any event and call the listeners as mapped in the EventsServiceProvider 
         * listeners array for this particular event.
         * @param object $event - The instance of the Event class to be dispatched. The object class must extend this class
         * 
         * @return void
         */
        public static function dispatch(Object $event) {
            if (! $event instanceof EventsClass) {
                throw new MyException("Dispatch parameter must be an instance of an EventsClass object");                
            }

            //get the [event => [listener1Class, Listenernclass]]
            $eventsAndListenersArray = EventsServiceProvider::$listeners;

            //check if the event is in the provider table
            $eventNamespaceClass = get_class($event);
            if(! array_key_exists($eventNamespaceClass, $eventsAndListenersArray)){
                throw new MyException("The Event class $class called is not maped in the EventsServiceProvider class listeners mapping");            
            }

            if (!is_array($eventsAndListenersArray[$eventNamespaceClass])) {
                throw new MyException("Your listeners in the EventsServiceProvider must be an array");
            }

            //get the listeners array for this event
            foreach ($eventsAndListenersArray as $key => $listenersArray) {
                if ($key == $eventNamespaceClass) {
                    //loop to instantiate the listeners with the event instance value
                    foreach ($listenersArray as $_ => $listeningClass) {
                        (new $listeningClass())->handle($event);
                    }
                }
            }
        }
    }
    
?>