<?php
    namespace App\Http\Exceptions;

    //This handles unacaught exceptions passed to it's constructor by set_exception_handler() callable function
    class Handler {
        /**
         * The exception caught and set by the handler constructor
         * @var \Throwable $ex - The exception instance
         */
        public $ex;

        public function __construct(\Throwable $ex) {
            $this->ex = $ex;
        }

        /**
         * The render method that shows what teh user sees after the exception occurs
         */
        public function render() {
            if (!$this->ex instanceof BaseException) {
                $GLOBALS["MODE"] == 1 ? $extraMsg = " File: ".$this->ex->getFile() . " Line: ".$this->ex->getLine() : '';
                $msg = $this->ex->getMessage().$extraMsg;
            } else {
                $msg = $this->ex->output();
            }

            if (requestIsAjax()) {
                echo responseJson([
                    "status" => 'error',
                    "message" => $msg,
                ], 500);
                return;
            }

            if ($this->ex instanceof QueryException) {
                echo $this->ex->show();
                return;
            }

            echo $msg;
            return;
        }
    }