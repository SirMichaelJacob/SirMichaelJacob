<?php
   $dns= 'localhost';
   $database='flipcom_db';
   $username = 'admin';
   $password = '';
   $pageTitle="Flip.com - Safe and Reliable Reporting";
    try
    {
        $pdo = new PDO("mysql:host=$dns;dbname=$database",$username,$password);
    }
    catch(PDOException $e)
    {

        $message = "Unable to load page resource.<br>Contact Admin.";
        include_once 'output.html.php';
        exit;
        //echo "Unable to connect to DB.".$e->getMessage();
    }
    /*$message = "Connected";
    include_once 'output.html.php';
    exit;;*/


?>