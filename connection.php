<?php
global  $dbuser , $dbpass , $db ; 
  $dbhost = "localhost";
  $dbuser  = "root";
  $dbpass  = "";
  $db  = "reservationappdb";

function getConn(){
 $conn = new PDO('mysql:dbname=reservationappdb;host=localhost;charset=utf8', 'root', '');
 $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
 $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $conn;
}
?>