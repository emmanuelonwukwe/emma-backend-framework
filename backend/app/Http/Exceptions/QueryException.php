<?php
    namespace App\Http\Exceptions;

    class QueryException extends BaseException {
        /**
         * The message to be output by this kind of exception 
         * 
         */
        public function show() {
            return '[Database unable to be queried]'.' Errors: '. $this->output();
        }
    }