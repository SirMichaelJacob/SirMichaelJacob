<?php
require_once 'access.php';

if(isset($_FILES['evidence']['name'])){

   $message="";

   //Generate Report Code///////////

    $codeText = "thisVeryRelevantMatterMustRemain";
    $textToArray = str_split($codeText);
    //print_r($strArr);
    $char1 = $textToArray[rand(0,strlen($codeText)-1)];
    $char2 = $textToArray[rand(0,strlen($codeText)-1)];
    $char3 = $textToArray[rand(0,strlen($codeText)-1)];
    //print_r($val);

    $codeNumber = rand(1000,1000000)+1; //Creates a random number between 1000 and 1 million

    $GLOBALS['reportCode'] =  "$char3$codeNumber$char1$char2";

    //$verificationCode = strtoupper(str_shuffle($preVerificationCode));
//////////////////////Upload Evidence////////////////////////////////////////////

        $target_dir = $_SERVER['DOCUMENT_ROOT']."/anon/confidential/"; //Target folder
        $target_file = $target_dir . basename($_FILES["evidence"]['name']); //Hold the file
        $uploadOk = 1;


        $fileExtension = pathinfo($target_file,PATHINFO_EXTENSION);
        // Valid extensions
        $valid_ext = array("png","jpg","mp3","mp4","3gp");

        if(in_array($fileExtension,$valid_ext))
        {
          $uploadOk = 1;

        }
        else
        {
            $uploadOk = 0;
          /*$message.=" Only accepts '.png','.jpg','.mp3','.mp4','.3gp'";*/
          $message="0";
        }

        //echo "$fileExtension";

        // Check file size
        if ($_FILES['evidence']['size'] > 12000000)
        {
            $uploadOk = 0;
            $message="0";
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0)
        {
           $message="0";
           echo $message;
        }
        else
        {
            //$GLOBALS['reportCode']= $GLOBALS['pdo']-> lastInsertId();
            if(move_uploaded_file($_FILES["evidence"]["tmp_name"], $target_file))
            {
               rename($target_file, $_SERVER['DOCUMENT_ROOT']."/anon/confidential/".$GLOBALS['reportCode'].".jpg");
               echo $GLOBALS['reportCode'];
            }
            else
            {
                echo $message;

            }
        }





}


?>