<?php
    namespace App\Http\Exceptions;

    use Exception;

    class BaseException extends Exception {
        /**
       * this is the error message the description
       */
        public $errorMessage;

      /**
        * This is a single Named code for the consumers to detect the type of error that occures eg DB_ERROR
        */
      public $errorNamedCode;
    
      public function __construct(string $errorMessage, string $errorNamedCode="GENERAL_ERROR"){
      $this->errorMessage=$errorMessage;
      $this->errorNamedCode=$errorNamedCode;
      }

        /**
         * extra message for developer to track the line error if
         * the @$showErrorFile=true;
         * @var string
         */
        private $extraMessage="";

        /**
         * This function gets the exception file name and line if exception instance object calls it
         * getLine() getFile() and getMessage() getCode() were inherited from the php core parent \Exception Class
         * the above functions are only effective if you dont override its arg position in the constructor of this
         * MyException class
         * 
         * @return string
         */
        
        public function errorFileName(){
          return sprintf("%s: %s %s: %s", 
          "@ErrorFilePath", 
          $this->getFile(),
          "@Error Thrown At Line", 
          $this->getLine()
          );
        }

        /**
         * modified exception message to be shown to the user
         *
         * @return string
         */
        public function output() : string
        {
          //check whether showExtraMessage should show up when in development mode
          if ($GLOBALS["MODE"] == 1 ) {
            $this->extraMessage = $this->errorFileName();
            $output=htmlspecialchars($this->errorMessage, ENT_COMPAT | ENT_HTML401). " ". $this->extraMessage;
          }
          else {
            $output=htmlspecialchars($this->errorMessage, ENT_COMPAT | ENT_HTML401);
          }

          return $output;
            
        }
    }