<?php

	require_once("./dbconn.php");
	session_start();
  $user_id = $_SESSION['id'];
  $gender = $_POST['inputGender'];
  $age = $_POST['inputAge'];
  $weight = $_POST['inputWeight'];
  $height = $_POST['inputHeight'];
  $exercise = $_POST['inputExercise'];
  $miscale = $_POST['inputMiscale'];
  $kcal = $_POST['inputCalory'];

  if($exercise==1.12) $exercise=2;
  else if($exercise==1.25) $exercise=3;
  else if($exercise==1.45) $exercise=4;

  $sql = "UPDATE user SET user_gender='$gender',
                          user_age='$age',
                          user_height='$height',
                          user_weight='$weight',
                          user_goal='$kcal',
                          user_check_inbody='$miscale',
                          user_check_exercise='$exercise'
											WHERE user_id='$user_id'";

	if (mysqli_query($conn, $sql)) {
		Header("Location:update://s$gender&h$height#q$age!");
	} // 성공 시 flag =1;
	else {
		Header("Location:./usermodify.php");
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
