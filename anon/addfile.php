<?php
require_once 'access.php';

  $id=$_POST['data'];
/////////////////////INSERT INTO DB//////////////////

    $userId = getUserId($_SESSION['flipemail']);
   try{
           $sql="Insert into reports set id=:id, userid=:userid,title=:title,description=:desc";
           $stmt = $GLOBALS['pdo']->prepare($sql);
           $stmt->bindValue(':id',$id);
           $stmt->bindValue(':userid',$userId);
           $stmt->bindValue(':title',htmlText($_POST['title']));
           $stmt->bindValue(':desc',htmlText($_POST['desc']));
           $stmt->execute();
   }
   catch(PDOException $e)
   {
        echo "error";

   }

    ////////////////////////////////////////////////////////////////////////////



?>