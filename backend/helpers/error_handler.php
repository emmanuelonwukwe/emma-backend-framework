<?php
    use App\Http\Exceptions\Handler;

    function customUncaughtExceptionHandler($exception) {
        $eMsg = $exception->getMessage();
        $eLine = $exception->getLine();
        $eFile = json_encode($exception->getFile());

        $log = json_encode([
            'msg' => 'Unhandled exception occured',
            "exMsg" => $eMsg,
            "exFile" => $eFile,
            "exLine" => $eLine,
            "date" => date("d/m/Y h:i:s")
        ]);

        $log.="\r\n";
        error_log($log, 3, storage_path('/logs/errors.log'));

        //call the uncaught exception handler class
        (new Handler($exception))->render();
    }

    function customErrorHandler($errorType, $errorMsg, $errorFIle, $errorLine, $errorCtx) {
        $errorFile = $errorFIle; //json_encode($errorFile ?? null);
        $errorCtx = $errorCtx; //json_encode($errorCtx);

        $log = json_encode([
            'msg' => 'Error occured',
            "errorType" => $errorType,
            "errorMsg" => $errorMsg,
            "errorFile" => $errorFile ?? null,
            "errorLine" => $errorLine,
            "errorCtx" => $errorCtx,
            "date" => date("d/m/Y h:i:s")
        ]);

        $log.="\r\n";

        error_log($log, 3, storage_path('/logs/errors.log'));
    }

    set_exception_handler('customUncaughtExceptionHandler');
    set_error_handler('customErrorHandler');
?>
    
