<?php
require_once("./dbconn.php");

echo "success";
#여기는 모든 음식 정보 가져오는 거!
##$sql = "SELECT * FROM foodinfo WHERE food_id=".$food_id;

##$result1 = mysqli_query($conn,$sql);

##$row = mysqli_fetch_array($result1);
session_start();
$user_code=$_SESSION['code'];

  $sql9 = "SELECT * FROM user WHERE user_code='$user_code'";
  $result = $conn->query($sql9);
    if ($result->num_rows > 0) { // 여러줄 가져오는 경우

	        while($row = $result->fetch_assoc()) {
			      $user_id = $row['user_id'];
			            $_SESSION['id'] = $user_id;
			          }

		  }

$food_id=$_POST["foodnum"];
$eaten_time=$_POST["eaten_time"];
$eaten_serving=$_POST["serving"];
$eaten_day = date('Y-m-d',time());
#if ( ! $conn ) {
#die( 'Could not connect: ' . mysqli_error($con) );
#} else {

$sql3="SELECT food_calory FROM foodinfo where food_id=$food_id";

$result=mysqli_query($conn,$sql3);

while($cal_row=mysqli_fetch_array($result)){

	$eaten_calory=$cal_row['food_calory'];
}

if($eaten_serving==0){
	$eaten_calory=$eaten_calory*0.5;
}
else{
	$eaten_calory=$eaten_calory*$eaten_serving;
}
print_r($_POST);
echo $eaten_calory;
$sql2 = "INSERT INTO eatenfood ( user_id, food_id, eaten_day, eaten_time, eaten_serving, eaten_calory) VALUES ( '$user_id', '$food_id','$eaten_day', '$eaten_time', '$eaten_serving','$eaten_calory')";

mysqli_query($conn,$sql2);
$conn->close();



header("Location:goback://application");
?>
