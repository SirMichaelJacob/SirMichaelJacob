<?php
 require_once 'access.php';

if(!isset($_POST['vetEmail']))
    {
        if(isset($_SESSION['loggedIn'])) //LoggedIn Users
        {
           $email = $_SESSION['flipemail'];
           vetReport($email);
        }

    }
    else{
        $email =  $_POST['vetEmail'];
        if(isUserEmail($email) or emailIsConfirmed($email))
        {
          vetReport($email);
        }
        else{
            echo "This Email is not registered on Flip.com" ;
        }

    }
    //echo findUserId(strtoupper($_POST['username']));
    //echo strtoupper($_POST['username']);

    function vetReport($email)
    {
       if(!hasVetted(strtoupper($email),getReportId($_POST['title'],findUserId($_POST['username']))))
        {
            $rId = getReportId($_POST['title'],findUserId($_POST['username']));
           try{
             $sql = 'Insert into vets set email=:email, reportid=:reportid';
             $stmt = $GLOBALS['pdo']->prepare($sql);
             $stmt->bindValue(':email',strtoupper($email));
             $stmt->bindValue(':reportid',$rId);
             $stmt->execute();
           }catch(PDOException $e)
           {

           }
           echo "successful";
        }
        else{
           echo "You have already vetted this report";
        }
    }

?>