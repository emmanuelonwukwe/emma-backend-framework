<?php
    //configure/override the ini session saving path for all the sessions of this application. Ensure that the storage/session dir exists
    session_save_path(storage_path("/sessions"));
    
    //This function gets the http base path of our app if called on null param or returns the url from base path/
    //Used mostly for redirect
    function base_path_http($uri = null) {
        //remove the starting slashes as the base path is already ending with slash
        $uri = preg_replace("/^\/+/", '', $uri);

        $project_folder = basename(dirname(dirname(__DIR__)));
        $domain_url = $_SERVER["REQUEST_SCHEME"]."://".$_SERVER["SERVER_NAME"];

        if ($project_folder == basename($_SERVER["DOCUMENT_ROOT"])) {
            $base_path = $domain_url."/";
        } else {
            $sub_dirs = preg_replace("/$project_folder*[^`]*/", "$project_folder/", $_SERVER["SCRIPT_NAME"]);
            $base_path = $domain_url.$sub_dirs;
        }

        return $base_path."$uri";
    }

    function base_path($uri = null) {
        $uri = preg_replace("/^\/+/", '', $uri);

        return dirname(dirname(__DIR__))."/$uri";
    }

    function backend_path($uri = null) {
        $uri = preg_replace("/^\/+/", '', $uri);

        return dirname(__DIR__)."/$uri";
    }

    function app_path($uri = null) {
        $uri = preg_replace("/^\/+/", '', $uri);

        return dirname(__DIR__)."/app/$uri";
    }

    function config_path($uri = null) {
        $uri = preg_replace("/^\/+/", '', $uri);

        return dirname(__DIR__)."/config/$uri";
    }

    function database_path($uri = null) {
        $uri = preg_replace("/^\/+/", '', $uri);

        return dirname(__DIR__)."/database/$uri";
    }

    function storage_path($uri = null) {
        $uri = preg_replace("/^\/+/", '', $uri);

        return dirname(__DIR__)."/storage/$uri";
    }