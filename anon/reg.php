<?php
require_once 'access.php';
include_once 'class.phpmailer.php';
include_once 'class.smtp.php';

 /*$email= $_POST['email'];
 $username = $_POST['username'];*/

if(isset($_POST['username']) and isset($_POST['email']))
{
    $message="";
    $email = strtoupper(htmlText($_POST['email']));
    $username = strtoupper(htmlText($_POST['username']));

    if(emailExist($email))
    {
      $message .= "<p>Email already registered</p>";
    }
    if(usernameExist($username))
    {
        $message .="<p> '".$_POST['username']."' has been used by another User. Please choose another username.</p>";
    }

    if(!usernameExist($username) and !emailExist($email))
    {
      try{
           $sql="Insert into user set username=:username, email=:email";
           $stmt = $pdo->prepare($sql);
           $stmt->bindValue(':username',strtoupper(htmlText($_POST['username'])));
           $stmt->bindValue(':email',$email);
           $stmt->execute();
        }
        catch(PDOException $e)
        {
            echo "error";

        }
        sendVerificationCode($email);
        $_SESSION['flipemail']=$email;
        echo "1";
    }
    else
    {
        echo $message;
    }
}


if(isset($_POST['code']))
{
    if(trueVerificationCode($_SESSION['flipemail'],htmlText($_POST['code'])))
    {
        ///change email_verification_status in SSdb/////////////////
        try{
           $sql="Update user set email_status=:status where email=:email";
           $stmt = $GLOBALS['pdo']->prepare($sql);
           $stmt->bindValue(':status','YES');
           $stmt->bindValue(':email',$_SESSION['flipemail']);
           $stmt->execute();
        }
        catch(PDOException $e)
        {
            echo "error";

        }
        $_SESSION['loggedIn']=TRUE;
        echo 'Email Verified';
        //echo $_SESSION['flipemail'];
    }
    else{
       //echo $_SESSION['flipemail'];
       echo 'Incorrect Verification Code';
    }
}

//echo 'Reg ran';

?>