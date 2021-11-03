<?php
    require 'confile.php';
    require 'session.php';
    $GLOBALS['reportCode']=""; 
     $GLOBALS['title']= "Flip.com - Safe Reporting";
     $GLOBALS['footerText'] ="Secure MedX - Developed by <a href='http://www.Cenfied.com.ng' style='color:red;'>Cenfied</a> &copy". date("Y");

     $mailDetails = array();  //Saves Appointment Details to send email

    ///Fetch all Users
    try
    {
      $sql="Select id,email,username,verification_code,email_status,verified from user";
      $result = $GLOBALS['pdo']->query($sql);

    }
    catch(PDOException $e)
    {
      $message = "Unable to fetch Site Users.";
      include 'output.html.php';
      exit;
    }

    foreach($result as $row)
    {
        $users[]= array('id'=>$row['id'],'email'=>$row['email'],'username'=>$row['username'],'email_status'=>$row['email_status'],'verified'=>$row['verified']);
    }

   ///------------------------------------///////////



   ///Fetch all Reportss
    try
    {
      $sql="SELECT reports.id,username,title,description,status FROM `reports` inner join user on userid=user.id";
      $result = $pdo->query($sql);

    }
    catch(PDOException $e)
    {
      $message = "Unable to fetch Site Users.";
      include 'output.html.php';
      exit;
    }

    foreach($result as $row)
    {
        $reports[]= array('id'=>$row['id'],'userId'=>$row['username'],'title'=>$row['title'],'desc'=>$row['description'],'status'=>$row['status']);
    }

   ///------------------------------------///////////
   ///Fetch all Vets
    try
    {
      $sql="SELECT user.id as userid,vets.email as email,reportid FROM `vets` inner join user on vets.email=user.email order by reportid ASC";
      $result = $pdo->query($sql);

    }
    catch(PDOException $e)
    {
      $message = "Unable to fetch Site Users.";
      include 'output.html.php';
      exit;
    }

    foreach($result as $row)
    {
        $vets[]= array('userId'=>$row['userid'],'email'=>$row['email'],'reportId'=>$row['reportid']);
    }
    //////////////////////////
    //$reportVets= array();
    foreach($reports as $rep)
    {
        $v= countVets($rep['id']);
      $reportVets[]=array('vets'=>$v,'reportId'=>$rep['id']);
    }

    arsort($reportVets);  //Sort by Vets

    if(count($reportVets)>0)
    {
           $count=0;
           foreach($reportVets as $rv)
           {   foreach($reports as $rep)
                {
                   if($rep['id']==$rv['reportId'] and $count<20)   //Get top 20 vetted Reports
                   {
                       $topReports[]=array('id'=>$rep['id'],'userId'=>$rep['userId'],'title'=>$rep['title'],'desc'=>$rep['desc'],'status'=>$rep['status'],'vets'=>$rv['vets']);
                       $count+=1;
                   }
                }
           }
    }




   ////Functions///////////////////


    function emailExist($email)
    {
        try
        {
            $sql="SELECT COUNT(*) from user where email=:email";
            $s=$GLOBALS['pdo']->prepare($sql);
            $s->bindValue(':email',$email);
            $s->execute();
        }
        catch (PDOException $e)
        {
            $loginError="Patient record Not found";
        }
        $row=$s->fetch();
        //$GLOBALS['myAccountType']=$row['type'];
        if($row[0]>0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    function usernameExist($username)
    {
        try
        {
            $sql="SELECT COUNT(*) from user where username=:username";
            $s=$GLOBALS['pdo']->prepare($sql);
            $s->bindValue(':username',strtoupper($username));
            $s->execute();
        }
        catch (PDOException $e)
        {
            $loginError="Record Not found";
        }
        $row=$s->fetch();
        //$GLOBALS['myAccountType']=$row['type'];
        if($row[0]>0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }



    function DatabaseContainsUser($email,$username)
    {
        try
        {
            $sql="SELECT id,username,email from user where email=:email and username=:username";
            $s=$GLOBALS['pdo']->prepare($sql);
            $s->bindValue(':email',$email);
            $s->bindValue(':username',strtoupper($username));
            $s->execute();
        }
        catch (PDOException $e)
        {
            $loginError="Record Not found";
        }
        $row=$s->fetch();
        //$GLOBALS['myAccountType']=$row['type'];
        if($row['email']==$email and $row['username']==$username)
        {
            /*$_SESSION['username']=$row['username'];
            $_SESSION['uId']= $row['id'];*/
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

 function emailVerified($email,$username)
 {

    try
        {
            $sql="SELECT email_status from user where email=:email and username=:username";
            $s=$GLOBALS['pdo']->prepare($sql);
            $s->bindValue(':email',$email);
            $s->bindValue(':username',strtoupper($username));
            $s->execute();
        }
        catch (PDOException $e)
        {
            $loginError="Paient record Not found";
        }
        $row=$s->fetch();
        //$GLOBALS['myAccountType']=$row['type'];
        if($row['email_status']=='YES')
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
 }

 function isUserEmail($email)
 {

    try
        {
            $sql="SELECT email_status from user where email=:email";
            $s=$GLOBALS['pdo']->prepare($sql);
            $s->bindValue(':email',$email);
            $s->execute();
        }
        catch (PDOException $e)
        {
            $loginError="Paient record Not found";
        }
        $row=$s->fetch();
        //$GLOBALS['myAccountType']=$row['type'];
        if($row['email_status']=='YES')
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
 }

 function emailIsConfirmed($email){
     $email=strtoupper($email);
       try
        {
            $sql="SELECT email_status from unreg where email=:email";
            $s=$GLOBALS['pdo']->prepare($sql);
            $s->bindValue(':email',$email);
            $s->execute();
        }
        catch (PDOException $e)
        {
            $loginError="Record Not found";
        }
        $row=$s->fetch();
        //$GLOBALS['myAccountType']=$row['type'];
        if($row['email_status']=='YES')
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
 }

 function userHasRole($userId,$role)
 {
     try
     {
         //$sql = "SELECT users.id, firstname, surname,email,phone,title from users INNER JOIN userrole on users.id = userrole.userId INNER JOIN roles on roles.id = userrole.roleId WHERE users.id = '1' and title='Admin'" ;
         $sql ="SELECT COUNT(*) from users INNER JOIN userrole on users.id = userrole.userId INNER JOIN roles on roles.id = userrole.roleId WHERE users.id =:userId and title=:role";
         $stmt= $GLOBALS['pdo']->prepare($sql);
         $stmt->bindValue(':userId',$userId);
         $stmt->bindValue(':role',$role);
         $stmt->execute();
     }
     catch(PDOException $e)
     {
         $message= "Unable to determine User Privileges";
     }
    $row=$stmt->fetch();
    if($row[0]==1)
    {
        //$message= $result[0];
        return TRUE;
    }
    else
    {
        //$message= $result[0];
        return FALSE;
    }


 }

 function trueVerificationCode($email,$code)
 {

      try
     {
         $sql ="SELECT COUNT(*) from user where email=:email and verification_code=:code";
         $stmt= $GLOBALS['pdo']->prepare($sql);
         $stmt->bindValue(':email',$email);
         $stmt->bindValue(':code',$code);
         $stmt->execute();
     }
     catch(PDOException $e)
         {
             $message= "Unable to determine User Status";
         }
     $row=$stmt->fetch();
     if($row[0]>0)
        {
            //$message= $result[0];
            return TRUE;
        }
        else
        {
            //$message= $result[0];
            return FALSE;
        }
 }

 function findUsername($email)
 {
    $sql = "Select username from user where email=:email";
    $stmt= $GLOBALS['pdo']->prepare($sql);
    $stmt->bindValue(':email',$email);
    $stmt->execute();
    $row = $stmt->fetch();

    return  $row[0];
 }


 function findUserId($username)
 {
     $username = strtoupper($username);
    try{
      $sql = "SELECT id FROM user WHERE username='".$username."'";
      $s = $GLOBALS['pdo']->prepare($sql);
      $s->execute();

    }
    catch(PDOException $e)
    {

    }
    $row = $s->fetch();

    return  $row[0];


 }
 function getUserId($email)
 {
     $email = strtoupper($email);
    try{
      $sql = "SELECT id FROM user WHERE email='".$email."'";
      $s = $GLOBALS['pdo']->prepare($sql);
      $s->execute();

    }
    catch(PDOException $e)
    {

    }
    $row = $s->fetch();

    return  $row[0];

 }

 function countUsersWithRole($roleTitle)
 {
   try
   {
         //$sql = "SELECT users.id, firstname, surname,email,phone,title from users INNER JOIN userrole on users.id = userrole.userId INNER JOIN roles on roles.id = userrole.roleId WHERE users.id = '1' and title='Admin'" ;
         $sql ="SELECT COUNT(*) from users INNER JOIN userrole on users.id = userrole.userId INNER JOIN roles on roles.id = userrole.roleId WHERE  title=:title";
         $stmt= $GLOBALS['pdo']->prepare($sql);
         $stmt->bindValue(':title',$roleTitle);
         $stmt->execute();
     }
     catch(PDOException $e)
     {
         $message= "Unable to find data";
     }
    $row=$stmt->fetch();
    if($row[0]>=0)
    {
        //$message= $result[0];
        return $row[0];
    }
    else
    {
        //$message= $result[0];
        return FALSE;
    }
 }
 function countWeekAppointments($status)
 {
    $week_array= getWeekStartAndEndDate(date('W'),date('Y'));
    try
    {
        $sql="SELECT COUNT(*) FROM appointments where app_date >=:startdate and app_date <= :enddate and status=:status";
         $stmt= $GLOBALS['pdo']->prepare($sql);
         $stmt->bindValue(':status',$status);
         $stmt->bindValue(':startdate',$week_array['week_start']);
          $stmt->bindValue(':enddate',$week_array['week_end']);
         $stmt->execute();
     }
     catch(PDOException $e)
     {
         $message= "Unable to find appointments";
     }
    $row=$stmt->fetch();
    if($row[0]>=0)
    {
        //$message= $result[0];
        return $row[0];
    }
    else
    {
        //$message= $result[0];
        return FALSE;
    }
 }

 function countAppointments($status)
 {
   try
   {
         //$sql = "SELECT users.id, firstname, surname,email,phone,title from users INNER JOIN userrole on users.id = userrole.userId INNER JOIN roles on roles.id = userrole.roleId WHERE users.id = '1' and title='Admin'" ;
         $sql ="SELECT COUNT(*) from appointments  WHERE app_date=CURDATE() and status=:status";
         $stmt= $GLOBALS['pdo']->prepare($sql);
         $stmt->bindValue(':status',$status);
         $stmt->execute();
     }
     catch(PDOException $e)
     {
         $message= "Unable to find appointments";
     }
    $row=$stmt->fetch();
    if($row[0]>=0)
    {
        //$message= $result[0];
        return $row[0];
    }
    else
    {
        //$message= $result[0];
        return FALSE;
    }
 }

 function hasVetted($email,$reportid)
 {
     $email =strtoupper($email);
   try
   {
         //$sql = "SELECT users.id, firstname, surname,email,phone,title from users INNER JOIN userrole on users.id = userrole.userId INNER JOIN roles on roles.id = userrole.roleId WHERE users.id = '1' and title='Admin'" ;
         $sql ="SELECT COUNT(*) from vets where email=:email and reportid=:reportid";
         $stmt= $GLOBALS['pdo']->prepare($sql);
         $stmt->bindValue(':email',htmlText($email));
         $stmt->bindValue(':reportid',$reportid);
         $stmt->execute();
     }
     catch(PDOException $e)
     {
         $message= "Unable confirm vets";
     }
    $rw=$stmt->fetch();
    /*if($row[0]>0)
    {
        //$message= $result[0];
        return TRUE;
    }
    else
    {
        //$message= $result[0];
        return FALSE;
    }*/
    return $rw[0];
 }
 function getReportId($title,$userId){
      try
       {
             //$sql = "SELECT users.id, firstname, surname,email,phone,title from users INNER JOIN userrole on users.id = userrole.userId INNER JOIN roles on roles.id = userrole.roleId WHERE users.id = '1' and title='Admin'" ;
             $sql ="SELECT id from reports WHERE title=:title and userid=:userid";
             $stmt= $GLOBALS['pdo']->prepare($sql);
             $stmt->bindValue(':title',htmlText($title));
             $stmt->bindValue(':userid',$userId);
             $stmt->execute();
         }
         catch(PDOException $e)
         {
             $message= "Unable confirm vets";
         }
        $row=$stmt->fetch();
        return $row[0];
 }

 function getWeekStartAndEndDate($week,$year) {
  $dto = new DateTime();
  $dto->setISODate($year, $week);
  $ret['week_start'] = $dto->format('Y-m-d');
  $dto->modify('+6 days');
  $ret['week_end'] = $dto->format('Y-m-d');
  return $ret;
}

 function htmlText($text)
 {
     return htmlspecialchars($text, ENT_QUOTES,'UTF-8');
 }

 function display($text)
 {
     echo htmlText($text);
 }


 function sendVerificationCode($userEmail)
 {
     //Generate Verification Code

          $codeText = "iSampleTextValueJugKingLifeWebStemZestRuleYin";
          $textToArray = str_split($codeText);
          //print_r($strArr);
          $char1 = $textToArray[rand(0,strlen($codeText)-1)];
          $char2 = $textToArray[rand(0,strlen($codeText)-1)];
          $char3 = $textToArray[rand(0,strlen($codeText)-1)];
          //print_r($val);

          $codeNumber = rand(1000,1000000)+1; //Creates a random number between 1000 and 1 million

          $preVerificationCode =  "$char3$codeNumber$char1$char2";

          $verificationCode = strtoupper(str_shuffle($preVerificationCode));
     //////////////////
     ///Insert verification code to db/////////////////
        try{
           $sql="Update user set verification_code=:code where email=:email";
           $stmt = $GLOBALS['pdo']->prepare($sql);
           $stmt->bindValue(':code',$verificationCode);
           $stmt->bindValue(':email',$userEmail);
           $stmt->execute();
        }
        catch(PDOException $e)
        {
            echo "error";

        }

     //////////////////Mail Code////////////////////

     /*
     $mail = new PHPMailer(); // the true param means it will throw exceptions on errors, which we need to catch

     $mail->IsSMTP(); // telling the class to use SMTP
    ////////////////////////Send mail to Customer Email/////////////////
            try {
                $mail->Host= "mail.secMedx.com.ng"; // SMTP server
                $mail->SMTPDebug = 1;                     // enables SMTP debug information (for testing)
                $mail->SMTPAuth= true;                  // enable SMTP authentication
                $mail->SMTPSecure = "ssl";// secure transfer enabled REQUIRED for Gmail
                $mail->Mailer  = "smtp";
                $mail->Host="mail.secMedx.com.ng"; // sets the SMTP server
                $mail->Port= 465;                 // set the SMTP port for the GMAIL server
                $mail->Username= "customer@secmedx.com.ng"; // SMTP account username
                $mail->Password   = "master101#";        // SMTP account password
                $mail->AddReplyTo('support@pazmog.com');
                $recipient=$userEmail;
                $mail->AddAddress("$recipient",'New User');
                $mail->SetFrom('customer@secMedx.com.ng','Secure MedX - User Registration');
                $mail->AddReplyTo('customer@secMedx.com.ng');
                $mail->Subject = "Secure MedX - User Registration";
                $mail->Body = "Welcome to Secure Medx Health management system. Your registration has been received.\r\n Your Verification Code is $verificationCode .\r\n Please visit https://www.secureMedX.com.ng/verify and enter the above verification code to confirm your registration.";
                //$mail->MsgHTML(file_get_contents('contents.html'));
                //$mail->AddAttachment('images/phpmailer.gif');      // attachment
                //$mail->AddAttachment('images/phpmailer_mini.gif'); // attachment
                $mail->Send();

                } catch (phpmailerException $e) {
                                //echo $e->errorMessage(); //Pretty error messages from PHPMailer
                } catch (Exception $e) {
                                //echo $e->getMessage(); //Boring error messages from anything else!
                }
            */
    //////..........
 }

 function countUserVets($email)
 {
    try
    {
        $sql = "SELECT Count(*) from vets where email='".$email."'";
        $s = $GLOBALS['pdo']->prepare($sql);
        $s->execute();
    }
    catch(PDOException $e)
    {

    }
    $row = $s->fetch();

    return $row[0];
 }
 function countUserReports($email)
 {
    try
    {
        $sql = "SELECT Count(*) from reports where userid='".getUserId($email)."'";
        $s = $GLOBALS['pdo']->prepare($sql);
        $s->execute();
    }
    catch(PDOException $e)
    {

    }
    $row = $s->fetch();

    return $row[0];
 }

 function countVets($reportId)
 {
    try
       {
        //$sql = "SELECT users.id, firstname, surname,email,phone,title from users INNER JOIN userrole on users.id = userrole.userId INNER JOIN roles on roles.id = userrole.roleId WHERE users.id = '1' and title='Admin'" ;
        $sql ="SELECT count(*) from vets WHERE reportid=:reportid";
        $stmt= $GLOBALS['pdo']->prepare($sql);
        $stmt->bindValue(':reportid',$reportId);
        $stmt->execute();
       }
         catch(PDOException $e)
         {
             $message= "Unable confirm vets";
         }
        $row=$stmt->fetch();
        return $row[0];
 }

 function getUserReports($userId)
 {
     ///Fetch all User Reports
    try
    {
      $sql="SELECT reports.id,username,title,description,status FROM `reports` inner join user on userid=user.id where userid='".$userId."'";
      $result = $GLOBALS['pdo']->query($sql);
    }
    catch(PDOException $e)
    {
      $message = "Unable to fetch Site Users.";
      include 'output.html.php';
      exit;
    }
    $userReports=[];
    foreach($result as $row)
    {
        $userReports[]= array('id'=>$row['id'],'userId'=>$row['username'],'title'=>$row['title'],'desc'=>$row['description'],'status'=>$row['status']);
    }

    return $userReports;

   ///------------------------------------///////////
 }

function cutText($text, $length) {

    if ($text == null) {
        return "";
    }
    if (strlen($text) <= $length) {
        return $text;
    }

    $text = substr($text,0, $length);
    $last = strrpos($text," ");
    $text = substr($text,0, $last);
    return $text . "...";
}
?>