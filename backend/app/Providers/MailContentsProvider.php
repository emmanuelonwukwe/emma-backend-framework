<?php
    namespace App\Providers;

    class MailContentsProvider {
        /**
         * This is the  layout in which all mail body contents will inherit
         * @param string $content - The mail content to be attached to the layout
         */
        public static function mailBodyLayout(string $content) : string {
            $protocol = $GLOBALS["site_url"];
            $site_url = $GLOBALS["site_url"];

            return <<<END
                <section class="mail-section" style="padding:10px">
                    <div class="container mail-container">
                        <div class=mail-row>
                            <div class='mail-col'>
                                <img src='$protocol://$site_url/assets/images/logo.png' >
                                <p style=color:green> $content </p>
                                <br><br>
                                <h1 style=color:orange>&#12861; Our events.</h1>
                                <table style=''>
                                    <thead style=background-color:red>
                                        <tr>
                                            <th>&#128454; Activity</th>
                                            <th>&#8986; Details</th>
                                        </tr>
                                    </thead>
                                    <tbody style="">
                                        <tr>
                                            <td>Caribiu visitataion</td>
                                            <td>Event to serve you well in our company</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <br/><br/><br/>
                        <hr>
                        <footer class=mail-footer>Thank you for being part of us at <a href='$protocol://$site_url'>$site_url</a>.</footer>
                    </div>
                </section>
            END;
        }

        /**
         * This is the registration mail content to be sent to the user on registered event fire
         */
        public static function registrationMailSubject() : string {
            return "Registration successful";
        }

        public static function registrationMailBody() : string {
            $protocol = $GLOBALS["site_url"];
            $site_url = $GLOBALS["site_url"];

            $body = "Welcome to our company we are glad to see you with us thanks.<hr>
            <a href='$protocol://$site_url'>$site_url</a> Click here to visit site.</a>";
            return static::mailBodyLayout($body);
        }

        /**
         * This is the login mail content to be sent to the user on login event fire
         */
        public static function loginMailSubject() : string {
            return "Login successful";
        }

        public static function loginMailBody() : string {
            $protocol = $GLOBALS["site_url"];
            $site_url = $GLOBALS["site_url"];
            $device = $_SERVER["USER_AGENT"];

            $body = <<<EOF
                The following device just logged in to your account now. $device<hr>
                <a href='$protocol://$site_url'>$site_url</a> Click here to visit help center if you do not know about this.</a>"
            EOF;
            return static::mailBodyLayout($body);
        }

        /**
         * This is the login mail content to be sent to the user on login event fire
         */
        public static function resetPasswordMailSubject() : string {
            return "Password Reset";
        }

        public static function resetPasswordMailBody($reset_key) : string {
            $protocol = $GLOBALS["site_url"];
            $site_url = $GLOBALS["site_url"];
            $device = $_SERVER["USER_AGENT"];

            $body = <<<EOF
                <br>
                Warning: If you did not request for a password reset link, please do not click on any link here. Report this mail activity to us.
                <hr>
                You requested for a password reset link which has been sent to you. Click on the link to continue.<hr>
                <a href='$protocol://$site_url/complete_reset_page.php?reset_key=$reset_key'>click to continue reset</a> Click here to visit help center if you do not know about this.</a>"
            EOF;
            return static::mailBodyLayout($body);
        }
    }