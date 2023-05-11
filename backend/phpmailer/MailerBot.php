<?php
    namespace PHPMailer;

    //this is the very first line in the php code 
    //#1 Load the namespace\ClassNames into this file so they can be used to create instances to avoid name conflicts
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    class MailerBot {
        /**
         * This is the function that sends the mail to the user or users (max of 200 recipients per hour, 500 mails daily limit)
         * @param string $subject - The subject of the mail
         * @param string $bodyHtml - The body of the mail which can also contain html tags
         * @param string $userEmail -The email of a user to which the mail will be sent to
         * @param array $usersEmailArray - The array of emails to which mail will be sent to if many users need the mail
         * @return string
         */
        public static function mailTo($subject, $bodyHtml, $userEmail = null, $usersEmailArray = [], $start = 1) {

            if (($userEmail==null && $usersEmailArray==[])) {
                return "Empty receivers. Please add the useremail or the users email array";
            }
    
            //#3 Create an instance; passing `true` enables exceptions
            $mail = new PHPMailer(true);
    
            try {
                $mailConfig = require config_path('/email.config');

                //Server settings
                $mail->SMTPDebug = 0;                      //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = $mailConfig["SMTP_MAILER_HOST"];                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = $mailConfig["SMTP_MAILER_HOST_USERNAME"];                     //SMTP username of the outgoing smtp
                $mail->Password   = $mailConfig["SMTP_MAILER_HOST_PASSWORD"];                               //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
                $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    
                //The email account of th above username or the email address on the same username panel
                $mail->setFrom($mailConfig["SMTP_MAILER_HOST_USERNAME"], $GLOBALS["site_fullname"]);
                
                //add the email accounts of the email receivers.
                if ($usersEmailArray !== []) {
                    //Add the user email recipient if his email is not empty
                    $userEmail !=null ? $mail->addAddress($userEmail) : "";

                    $buffToAddr = [];
                    $toOnlyAddresses = array_filter($usersEmailArray, function($index) use($start) {
                        return $index >= $start - 1 AND $index <= $start + 198;
                    }, ARRAY_FILTER_USE_KEY);

                    foreach($toOnlyAddresses as $index => $emailVal){
                        
                        if ($start <= count($usersEmailArray) && $start > 0 ) {

                            if ($mail->validateAddress($emailVal)) {
                                $buffToAddr[] = $emailVal;
                                $index == $start - 1 ? $mail->addAddress($emailVal): $mail->addBCC($emailVal);     //Add a recipient
                            }
                            
                            
                        } else {
                            throw new Exception("start must begin from 1 and end stop at" . count($usersEmailArray));
                        }
                        
                    }
                }
                else {
                    //just add the useremail
                    $mail->addAddress($userEmail);
                }
    
                //$mail->addAddress('devprox202@gmail.com', 'Joe User');     //Add a recipient
                // $mail->addAddress('ellen@example.com');               //Name is optional
                // $mail->addReplyTo('info@example.com', 'Information');
                // $mail->addCC('cc@example.com');
                // $mail->addBCC('bcc@example.com');
    
                //Attachments
                //$mail->addAttachment();         //Add attachments
                //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
    
                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = $subject;
                $mail->Body    =  $bodyHtml;
                
                // echo "To filtered addresses: ";
                // print_r($toOnlyAddresses);
                // echo "<br><br>To validated addresses<br><br><br>";
                // print_r($buffToAddr);

                //smtp only allows 200 mails per hour
                $mail->send();

                return 'Message sent successfully';
            } catch (Exception $e) {
                throw $e;
                // $e->errorMessage();
                //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
    }

    

?>
