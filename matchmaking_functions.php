<?php

/*
 * Functions for getting data relevant for matchmaking from the database, and to output these
 * to the user
 */

include 'connect.php';
function getUserInterests($u)
{
      $ids = $u['interests'];
      var_dump($ids);
      $ids = explode(',', $ids);  
      var_dump($ids);
      $ids = implode(',', $ids);
      var_dump($ids);
      $sql = "SELECT name FROM interests WHERE ID IN ($ids)";
      $result = mysql_query($sql, $con);
      var_dump($result);
}

?>