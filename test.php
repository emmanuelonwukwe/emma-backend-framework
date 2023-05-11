<?php
    require_once __DIR__.'/vendor/autoload.php';
    authPanelChecker(); //session needs to start for csrf_token form
?>

<!Doctype html>
<html>
    <head>
        <meta name=viewport content="width=device-width, initial-scale=1, user-scalable=1" >
        <style>
            .demo{
                color: red;
            }
        </style>
    </head>
    <body>
        <h1>API Route examples</h1>
        <button onclick="registerApiCall()">click to call the Register api </button>
        <hr>
        <button onclick="loginApiCall()">click to call the Login api </button>
        <hr>
        <button onclick="logoutApiCall()">click to call the Logout api </button>

        <hr>
        <button onclick="authUserDataApiCall()">click to call the authUserData api </button>

        <div class=demo></div>

        <h1>Register Form test</form>
        <form method=POST action=testapi.php>
            <input name=csrf_token value=<?= $csrf_token = csrf_token(); ?> placeholder=role hidden>
            <input name=email value="" placeholder=Email>
            <input name="password" value=1 placeholder=Email>
            <input name=action value=register type=submit>
        </form>


        <h1>Login Form test</form>
        <form method=POST action=testapi.php enctype=multipart/form-data>
            <input name=csrf_token value=<?= $csrf_token; ?> placeholder=role hidden>
            <input name="email" value='aj' placeholder=Email>
            <input name="password" value='' placeholder=Password>
            <input name="myimage[]" type='file' accept=".jpg, .png, .jpeg" multiple>
            <input name=action value=login type=submit>
        </form>


        <script src="./assets/js/jquery-1.12.4.min.js"></script>
        <script>
            function registerApiCall() {
                $.ajax({
                    url: 'testapi.php',
                    type: "POST",
                    data: {email: 'ajaxo', password: 1, role: 'userx', csrf_token: 12345, "action": "register"},
                    success: function(responseText){
                        $(".demo").html(responseText);

                        //store the token in local store
                        var $obj = JSON.parse(responseText);
                        localStorage.setItem('Token', $obj.Token);
                    },

                    error: function (xhr){
                        alert(xhr.responseText);
                        //console.log(xhr.responseText);
                    }
                });
            }

            function loginApiCall() {
                $.ajax({
                    url: 'testapi.php',
                    type: "POST",
                    data: {email: 'aja', password: 1, "action": "login"},
                    success: function(responseText){
                        $(".demo").html(responseText);
                        //store the token in local store
                        var $obj = JSON.parse(responseText);
                        localStorage.setItem('Token', $obj.Token);
                    },

                    error: function (xhr){
                        alert(xhr.responseText);
                        //console.log(xhr.responseText);
                    }
                });
            }

            function logoutApiCall() {
                $.ajax({
                    url: 'testapi.php',
                    type: "POST",
                    data: {email: 'aja', password: 1, "action": "logout"},
                    //for cross-site/ unAuthenticated requests headers: {"Token": localStorage.getItem('Token')},
                    success: function(responseText){
                        $(".demo").html(responseText);
                        //store the token in local store
                        var $obj = JSON.parse(responseText);
                        localStorage.removeItem('Token');
                    },

                    error: function (xhr){
                        alert(xhr.responseText);
                        //console.log(xhr.responseText);
                    }
                });
            }

            function authUserDataApiCall() {
                $.ajax({
                    url: 'testapi.php',
                    type: "POST",
                    data: {"action": "auth_user_data"},
                    //for cross-site/ unAuthenticated requests headers: {"Token": localStorage.getItem('Token')},
                    success: function(response){
                        $(".demo").html(response);

                    },

                    error: function (xhr){
                        alert(xhr.responseText);
                        //console.log(xhr.responseText);
                    }
                });
            }
        </script>
    </body>
</html>