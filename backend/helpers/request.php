<?php
    //This function checks if a request to this server is made wit xmlhttprequest
    function requestIsAjax() {
        if (isset($_SERVER["HTTP_X_REQUESTED_WITH"])) {
            return strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ? true : false;
        }

        return false;
    }

    //This function converts your response to json and also sets the http response status code of the url
    function responseJson(array $dataArrayToEncode, $http_status_code = 200) {
        //header("HTTP/1.1 $http_status_code Code Message eg unauthorized"); OR below
        http_response_code($http_status_code);
        return json_encode($dataArrayToEncode);
    }