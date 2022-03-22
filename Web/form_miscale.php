<?php
session_start();
  require_once("./dbconn.php");
echo "success";
$user_code=$_GET['code'];
$_SESSION['code']=$user_code;
echo $user_code;
print_r($_POST);
$sql8 = "SELECT * FROM user WHERE user_code='$user_code'";
  $result5 = $conn->query($sql8);

if ($result5->num_rows > 0) { // 여러줄 가져오는 경우
	  while($row = $result5->fetch_assoc()) {
			    $user_id = $row['user_id'];
			    $_SESSION['id'] = $user_id;
		    }
  }

echo "userid= ".$user_id."!";

//  $user_id=$_SESSION['id'];
  $today = date("Y-m-d");

  $inbody_weight = $_POST['weight'];
  $inbody_muscle = $_POST['muscle'];
  $inbody_bmi = $_POST['bmi'];
  $inbody_fat = $_POST['fat'];
  $flag;
  echo $inbody_weight . " " . $inbody_muscle . " " . $inbody_bmi . " " . $inbody_fat. "fin";
  $sql = "SELECT * FROM inbody WHERE user_id='$user_id' AND inbody_day='$today'"; // 오늘 이미 인바디 측정한경우
  $result = $conn->query($sql);
  $flag=0;
  if($row = $result->fetch_assoc()) {
    $flag=1; // 오늘 미밴드에 업데이트가 이미 되어있는 경우 -> update
  }
  else {
    $flag=0;
  }

  if($flag==1) // 원래 있었던 경우  업데이트
  {
    $sql1= "UPDATE inbody SET inbody_weight ='$inbody_weight', inbody_muscle='$inbody_muscle', inbody_bmi='$inbody_bmi', inbody_fat='$inbody_fat' WHERE user_id='$user_id' and inbody_day='$today'";
    if (mysqli_query($conn, $sql1)) {
  		#Header("Location:./static.php");
  	}
  	else {
  		#echo "미스케일 정보 업데이트 오류 !";
  	}
  }
  else{ // 없었던 경우 insert
    $sql2 = "INSERT INTO inbody(user_id,inbody_weight,inbody_muscle,inbody_bmi,inbody_fat,inbody_day) VALUES ('$user_id','$inbody_weight','$inbody_muscle','$inbody_bmi','$inbody_fat','$today')";
  	if (mysqli_query($conn, $sql2)) {
  		#Header("Location:./static.php");
  	}
  	else {
  		#echo "미스케일 정보 인서트 오류 !";
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
</head>

<body>
</body>
</html>
