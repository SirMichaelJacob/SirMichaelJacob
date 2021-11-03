<?php
    require_once 'access.php';
    include_once 'class.phpmailer.php';
    include_once 'class.smtp.php';

    $email_pass=FALSE;
    $username_pass= FALSE;

if(isset($_POST['username']) and isset($_POST['email']))
{
    $message="";
    $email = strtoupper(htmlText($_POST['email']));
    $username = strtoupper(htmlText($_POST['username']));


    if(emailExist($email))
    {
      $email_pass= TRUE;

    }
    if(usernameExist($username))
    {
        $username_pass= TRUE;
    }

    if($username_pass and $email_pass and DatabaseContainsUser($email,$username))
    {
       if(emailVerified($email,$username))
        {
            $message="verified";
           //Log User In
            $_SESSION['loggedIn']= TRUE;
            $_SESSION['flipemail']= $email;
        }
        else
        {
            $message="unverified";
            $_SESSION['flipemail']= $email;
        }

    }
    else{
        $message= 'User not found';
    }

    echo $message;



}


?>