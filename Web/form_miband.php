<?php

session_start();
echo "success";
  require_once("./dbconn.php");
$user_code=$_GET['code'];
  $sql8 = "SELECT * FROM user WHERE user_code='$user_code'";
  $result8 = $conn->query($sql8);
    if ($result8->num_rows > 0) { // 여러줄 가져오는 경우

	        while($row = $result8->fetch_assoc()) {
			      $user_id = $row['user_id'];
			            $_SESSION['id'] = $user_id;
			          }

		  }



	$user_id=$_SESSION['id'];
  $today = date("Y-m-d");

  $walk_cal = $_POST['walk_cal'];
  $walk_dist = $_POST['walk_dist'];
  $walk_min = $_POST['walk_min'];

  $run_cal = $_POST['run_cal'];
  $run_dist = $_POST['run_dist'];
  $run_min = $_POST['run_min'];

  $walk_flag;
  $run_flag;
  // 걷기정보
  $sql = "SELECT * FROM doexercise WHERE user_id='$user_id' AND exercise_id=1 AND doexercise_day='$today'"; // 오늘 걷기값이 이미 있는경우
  $result = $conn->query($sql);
  if($row = $result->fetch_assoc()) {
    $walk_flag=1; // 오늘 미밴드에 업데이트가 이미 되어있는 경우 -> update
  }
  else {
    $walk_flag=0;
  }

  if($walk_flag==1) // 원래 있었던 경우  업데이트
  {
    $sql1= "UPDATE doexercise SET doexercise_calory ='$walk_cal', doexercise_minute='$walk_min', doexercise_distance='$walk_dist' WHERE user_id='$user_id' AND exercise_id=1 and doexercise_day='$today'";
    if (mysqli_query($conn, $sql1)) {
  	#	Header("Location:./index.php");
  	}
  	else {
  	}
  }
  else{ // 없었던 경우 insert
    $sql4 = "INSERT INTO doexercise(user_id,exercise_id,doexercise_day,doexercise_calory,doexercise_minute,doexercise_distance) values ('$user_id',1,'$today','$walk_cal','$walk_min','$walk_dist')";
  	if (mysqli_query($conn, $sql4)) {
  	#	Header("Location:./index.php");
  	}
  	else {
  	}
  }

  //달리기 2
  $sql2 = "SELECT * FROM doexercise WHERE user_id='$user_id' AND exercise_id=2 AND doexercise_day='$today'"; // 오늘 걷기값이 이미 있는경우
  $result = $conn->query($sql2);
  if($row = $result->fetch_assoc()) {
    $run_flag=1; // 오늘 미밴드에 업데이트가 이미 되어있는 경우 -> update
  }
  else {
    $run_flag=0;
  }

  if($run_flag==1)
  {
    $sql3= "UPDATE doexercise SET doexercise_calory ='$run_cal', doexercise_minute='$run_min', doexercise_distance='$run_dist' WHERE user_id='$user_id' AND exercise_id=2 and doexercise_day='$today'";
    if (mysqli_query($conn, $sql3)) {
  	#	Header("Location:./index.php");
  	}
  	else {
  	}
  }
  else{ // 없었던 경우 insert
    $sql5 = "INSERT INTO doexercise(user_id,exercise_id,doexercise_day,doexercise_calory,doexercise_minute,doexercise_distance) values ('$user_id',2,'$today','$run_cal','$run_min','$run_dist')";
  	if (mysqli_query($conn, $sql5)) {
  	#	Header("Location:./index.php");
  	}
  	else {
  	}
  }
	$conn->close();

?>

<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<html>

<head>
  <title></title>
  <meta charset="utf-8">
  <!-- 외부 css -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css2?family=Do+Hyeon&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./css/jaehyun.css?ver=2">
  <!-- 아이콘 -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/all.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/v4-shims.css">
  <!-- 스크립트 -->

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
  integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
  crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
  <script src="./js/nav.js"></script>
</head>

<body>
</body>
</html>
