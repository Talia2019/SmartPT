<?php

  require_once("./dbconn.php");
  session_start();
  $user_code =  $_POST['code']; //수정해야함!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
  $is_user = $_POST['isNew'];//수정해야함!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
  $is_user=0;
  if($is_user==1) // 기존 유저인 경우
  {
    $sql = "SELECT * FROM user WHERE user_code='$user_code'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) { // 여러줄 가져오는 경우
      while($row = $result->fetch_assoc()) { // 해당되는 코드가 데베이있는경우
        $_SESSION['code'] = $row['user_code'];
      }

      echo "success";
    }
    else { // 없는 경우
      echo "fail";
    }
  }
  else{ // 신규 유저인 경우
    $sql = "SELECT * FROM user WHERE user_code='$user_code'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) { // 여러줄 가져오는 경우
      while($row = $result->fetch_assoc()) { // 해당되는 코드가 데베이있는경우 코드 중복
        echo "fail"; // 코드 중복되서 다시 코드 받아야함 !
      }
    }
    else { // 없는 경우
	    $_SESSION['code']= $user_code;
	    echo $_SESSION['code'];
	    //Header("Location:userinsert.php");
	echo "none";
    }

  }
?>
