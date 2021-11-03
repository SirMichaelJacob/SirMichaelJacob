<?php
require 'access.php';
$message = "";
$reporter ='';

if(!isset($_SESSION['loggedIn']))
{


    if(isset($_SESSION['flipemail']) and !isset($_SESSION['loggedIn']))
    {
     include 'verify.html';
     exit;
    }

}

//SEARCH FOR REPORTS
if(isset($_GET['search']) or isset($_GET['page']))
{
    $rowsPerPage=5;
    $search = htmlText($_GET['search']);
    $searchUp = strtoupper($search);
    $usernameUp = strtoupper($search);


    $selected=0;


    if(!(isset($_GET['page'])))
    {
        $page=0;
    }
    else
    {
        $page = (($_GET['page'])-1)* $rowsPerPage;
        $selected= $_GET['page'];
    }

    try
    {
      $sql="SELECT reports.id,username,title,description,status FROM `reports` inner join user on userid=user.id where username LIKE '". "%$search%"."' OR username LIKE '"."%$usernameUp%"."' OR title LIKE '"."%$search%". "' OR description LIKE '"."%$search%". "' OR description LIKE '"."%$searchUp%"."'"." ORDER BY username ASC LIMIT $page , $rowsPerPage";
      $result = $GLOBALS['pdo']->query($sql);
    }
    catch(PDOException $e)
    {
      $message = "Unable to search";
    }

    foreach($result as $row)
    {
        $searchReports[]= array('id'=>$row['id'],'userId'=>$row['username'],'title'=>$row['title'],'desc'=>$row['description'],'status'=>$row['status']);
    }

    if(isset($searchReports))
    {
        try
        {
            $sql1="SELECT reports.id,username,title,description,status FROM `reports` inner join user on userid=user.id where username LIKE '". "%$search%"."' OR username LIKE '"."%$usernameUp%"."' OR title LIKE '"."%$search%". "' OR description LIKE '"."%$search%". "' OR description LIKE '"."%$searchUp%"."'";
            $result = $pdo-> query($sql1) ;
        }
        catch(PDOException $e)
        {
            $mesage ="Failed to load Search";
            //explode()
        }
        foreach($result as $row)
        {
            $AllResult[] = array('id'=>$row['id'],'userId'=>$row['username'],'title'=>$row['title'],'desc'=>$row['description'],'status'=>$row['status']);
        }



        $numberOfRecords = count($AllResult);
        $val = $numberOfRecords/$rowsPerPage;
        $maxPage = ceil($val);

        $GLOBALS['message'] ="This table is sortable, click a column to sort";

    }
    else{
            $GLOBALS['message']= "Search Returned Empty";
    }


    include 'search.html';
    exit;
}

////Show case for Vetting///
if(isset($_GET['rcase']) and isset($_GET['rep']))
{
    $_GET['search']= htmlText($_GET['rcase']);

    $GLOBALS['reporter'] = htmlText($_GET['rep']);
    //$search =$_GET['search'];

    //header('Location:?search='.$_GET['search']);
    include 'search.html';
    exit;
}
/////////
if(isset($_GET['about']))
{
    include 'about.html';
    exit;
}

///////
if(isset($_POST['logout']))
{
        //$_COOKIE['signedIn']="False";
        unset($_SESSION['flipemail']);
        unset($_SESSION['loggedIn']);
        header('Location:.');
        exit;
}
 if(isset($_GET['report']))
 {
    include 'report.html';
    exit;
 }




include 'home.html';
exit;


?>
