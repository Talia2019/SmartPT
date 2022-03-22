<?php

error_reporting(0);

  $host = 'localhost';
  $db_user = 'root';
  $db_pw = 'toor'; #toor
  $db_name = 'smartpt';
  $conn = new mysqli($host, $db_user, $db_pw, $db_name);



   mysqli_query($conn, "set session character_set_connection=utf8;");

   mysqli_query($conn, "set session character_set_results=utf8;");

   mysqli_query($conn, "set session character_set_client=utf8;");

  if ($conn->connect_error) {
    printf("Connect failed: %s\n", $conn->connect_error);
  exit();
  }
  else{
    //echo $db_name;
  }
?>
