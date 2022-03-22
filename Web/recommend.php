
<?php
session_start();
require_once("./dbconn.php");
$sex=50;
$height=40;
$weight=30;
$today = date("Y-m-d");
$kcal = 0; //칼로리
$car =0; // 탄수화물
$fat =0; // 지방
$pro =0; // 단백질

$user_id=$_SESSION['id'];
$sql = "SELECT * FROM user WHERE user_id='$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) { // 여러줄 가져오는 경우

  while($row = $result->fetch_assoc()) {
    $user = $row;
  }
}
else {
  echo "유저 접속 오류";
}
//
$maxcar = $user['user_goal']* 0.65* 0.25;
$maxfat =$user['user_goal'] *0.2* 0.1;
$maxpro =$user['user_goal'] *0.15* 0.25;

$maxcar=(int)$maxcar;
$maxfat=(int)$maxfat;
$maxpro=(int)$maxpro;
// 오늘 먹은 음식 조회
$sql1 = "SELECT * FROM eatenfood INNER JOIN foodinfo on eatenfood.food_id = foodinfo.food_id WHERE user_id='$user_id' and eaten_day='$today'";
$result1 = $conn->query($sql1);

if ($result1 -> num_rows>0) { // 여러줄 가져오는 경우

  while($row = $result1->fetch_assoc()) {
    if($row['eaten_serving']==0)
{ # 0인분인경우
 $kcal = $kcal + $row['food_calory']*0.5;
 $car = $car + $row['food_car']*0.5;
 $fat = $fat + $row['food_fat']*0.5;
 $pro = $pro + $row['food_pro']*0.5;
}
else
{
 $kcal = $kcal + $row['food_calory']*$row['eaten_serving'];
 $car = $car + $row['food_car']*$row['eaten_serving'];
 $fat = $fat + $row['food_fat']*$row['eaten_serving'];
 $pro = $pro + $row['food_pro']*$row['eaten_serving'];
}
if($row['eaten_time']==1) // 아침인경우
{

}
}
}
else //echo "0 results";


?>

<!DOCTYPE html>
<html>
<head>
  <title></title>

  <link
  rel="stylesheet"
  href="https://use.fontawesome.com/releases/v5.14.0/css/all.css">
  <link
  rel="stylesheet"
  href="https://use.fontawesome.com/releases/v5.14.0/css/v4-shims.css">
  <link rel="stylesheet" href="/resource/css/bootstrap.css">
  <link href="https://fonts.googleapis.com/css2?family=Do+Hyeon&display=swap" rel="stylesheet">
  <link
  rel="stylesheet"
  href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
  integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
  crossorigin="anonymous">


  <script src="./js/nav.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
  integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
  crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

</style>

<link rel="stylesheet" href="./css/jaehyun.css?ver=2">

</script>

<script>
  function myFunction() {
  // Declare variables
  var input, table, tr, td, i, txtValue,tv,iv,count;

  input = document.getElementById("myInput");
  table = document.getElementById("user-table");
  tr = table.getElementsByTagName("tr");


  // Loop through all table rows, and hide those who don't match the search query
  for (i = 1; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[5];
    tv=(parseInt(($(td).html())));
    iv=(parseInt($(input).val()));
    count=0;
    if((iv>=tv)){
      tr[i].style.display = "";
      count=count+1;
    } else {
      tr[i].style.display = "none";
    }
  }
}

</script>
<style>
  .box {
    height:450px;
    overflow-y:scroll;
  }

  #btn1{
   width:80%;
   font-size:40px;
   background-color:#8da5bd
 }
</style>
</head>
<body>
  <nav class="navbar fixed-top">
    <p class="navp">SmartPT</p>
    <a href="usermodify.php">
     <i class="fa fa-user-circle navi"></i>
   </a>
 </nav>

 <br>

 <div clas="container">
   <div style="margin-left:50px;">
     <p style="font-size:80px;">오늘의 영양소</p>
     <p class="mb-2 text-muted" style="font-size:70px;">Today's nutrients</p>
   </div>
</div>
<br>
<div class="card nutrients-card" style="margin:0 100px; height:500px;">
  <div class="card-body">
    <br><br>
    <div style="text-align:center" data-toggle="modal" data-target="#exampleModal" >
      <p style="margin-right:20px; font-size:40px;" > 칼로리 </p>
      <div class="progress" style="height:30px;" >
	<div class="progress-bar <?php if($kcal<$user['user_goal']){
echo "bg-success";}else echo "bg-danger";?>" id="progress_kcal" role="progressbar" style="width:  <?php echo $kcal/$user['user_goal']*100;?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
      </div>
      <p style="font-size:30px;"><?php echo (int)($kcal)."/".$user['user_goal'];?></p>

    </div>




    <br><br><br>
    <div class="carprofat">
      <div style="float:left; width:150px; text-align:center; margin:0 70px;">
        <p style="font-size:40px;"> 탄수화물 </p>
        <div class="progress" >
          <div class="progress-bar bg-info" role="progressbar" style="width: <?php echo $car/$maxcar*100;?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"> </div>
        </div>
        <p style="font-size:30px;"><?php echo (int)($car)."/".$maxcar;?></p>
      </div>
      <div style="float:left; width:150px; text-align:center; margin-right: 70px;">
        <p style="font-size:40px;"> 단백질 </p>
        <div class="progress" >
          <div class="progress-bar bg-info" role="progressbar" style="width: <?php echo $pro/$maxpro*100;?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"> </div>
        </div>
        <p style="font-size:30px;"><?php echo (int)($pro)."/".$maxpro;?></p>
      </div>
      <div style="float:left; width:150px; text-align:center;">
        <p style="font-size:40px;"> 지방 </p>
        <div class="progress" >
          <div class="progress-bar bg-info" role="progressbar" style="width: <?php echo $fat/$maxfat*100;?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <p style="font-size:30px;"><?php echo (int)($fat)."/".$maxfat;?></p>
      </div>
    </div>
  </div>
</div>

<br><br>


<?php
// 아침에 먹은 식단을 가져온다.
$sql3 = "SELECT * FROM eatenfood INNER JOIN foodinfo on eatenfood.food_id = foodinfo.food_id WHERE user_id='$user_id' and eaten_day='$today' and eaten_time=1";
$result3 = $conn->query($sql3);

if ($result3 -> num_rows>0) { // 여러줄 가져오는 경우

//아침에 먹은게 하나라도 있는 경우
  $is_morning =1;
}
else{
// 아침에 먹은게 없는경우에는 카메라 아이콘 보여준다.
  $is_morning =0;
}

// 점심에 먹은 식단을 가져온다.
$sql4 = "SELECT * FROM eatenfood INNER JOIN foodinfo on eatenfood.food_id = foodinfo.food_id WHERE user_id='$user_id' and eaten_day='$today' and eaten_time=2";
$result4 = $conn->query($sql4);

if ($result4 -> num_rows>0) { // 여러줄 가져오는 경우

//점심에 먹은게 하나라도 있는 경우
  $is_lunch =1;
}
else{
// 점심에 먹은게 없는경우에는 카메라 아이콘 보여준다.
  $is_lunch =0;
}

// 저녁에 먹은 식단을 가져온다.
$sql5 = "SELECT * FROM eatenfood INNER JOIN foodinfo on eatenfood.food_id = foodinfo.food_id WHERE user_id='$user_id' and eaten_day='$today' and eaten_time=3";
$result5 = $conn->query($sql5);


if ($result5 -> num_rows>0) { // 여러줄 가져오는 경우

//저녁에 먹은게 하나라도 있는 경우
  $is_dinner =1;
}
else{
// 저녁에 먹은게 없는경우에는 카메라 아이콘 보여준다.
  $is_dinner =0;
}

?>

<br>
<br>
<div style="margin-left:50px;">
  <p style="font-size:80px;">오늘의 식단</p>
  <p class="mb-2 text-muted" style="font-size:70px;">Today's Diets</p>
  <br>
</div>

<div class="card">
  <div class="card-header card-header1">
    <ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
      <li class="nav-item" role="presentation">
        <a class="nav-link active rea" id="home-tab" data-toggle="tab" href="#breakfast" role="tab" aria-controls="home" aria-selected="true">아침</a>
      </li>
      <li class="nav-item" role="presentation">
        <a class="nav-link rea" id="profile-tab" data-toggle="tab" href="#lunch" role="tab" aria-controls="profile" aria-selected="false">점심</a>
      </li>
      <li class="nav-item" role="presentation">
        <a class="nav-link rea" id="contact-tab" data-toggle="tab" href="#dinner" role="tab" aria-controls="contact" aria-selected="false">저녁</a>
      </li>
    </ul>
  </div>


  <div class="tab-content" id="myTabContent" >
    <div class="tab-pane fade show active" id="breakfast" role="tabpanel" aria-labelledby="home-tab">

      <div class="card-body" id="camera_1" style="text-align: center; ">
        <a href="app://1"><button class="btn btn-secondary"id="btn1">음식입력</button></a>
        <!--  <button type="submit"> <i class="fas fa-utensils cardi" style="font-size:100px;"aria-hidden="true"></i></button>-->

      </div>



      <?php
      if($is_morning==1){
        $morning_kcal =0;
        $morning_car =0;
        $morning_pro =0;
        $morning_fat =0;
        while($row = $result3->fetch_assoc()) {
          if ($row['eaten_serving']==0){
            $morning_kcal = $morning_kcal+$row['food_calory']*0.5;
            $morning_car = $morning_car+$row['food_car']*0.5;
            $morning_pro = $morning_pro+$row['food_pro']*0.5;
            $morning_fat = $morning_fat+$row['food_fat']*0.5;
          }
          else{
            $morning_kcal = $morning_kcal+$row['food_calory']*$row['eaten_serving'];
            $morning_car =  $morning_car+$row['food_car']*$row['eaten_serving'];
            $morning_pro =  $morning_pro+$row['food_pro']*$row['eaten_serving'];
            $morning_fat =  $morning_fat+$row['food_fat']*$row['eaten_serving'];

          }
        }
      }
      ?>



      <div style="margin:20px 30px;" class="smallbar">
        <div style="text-align : center;">
          <br>
          <div style=" text-align:center;" data-toggle="modal" data-target="#exampleModal" >
            <div style="float: left;padding-bottom:3px;">
              <p style="font-size:40px; " > 칼로리 <?php echo (int)($morning_kcal);
              if ($morning_kcal==0){
                echo 0;
              }?></p>
            </div>

            <div style="float: right;">
              <div style="float:left; text-align:center; margin:20px 10px 0 100px;">
                <p style="font-size:30px; float:left; margin-right: 7px;"> 탄 </p><p style="font-size:30px;float:left;" class="text-info"><?php echo (int)($morning_car);
                if ($morning_kcal==0){
                  echo 0;
                }?></p>

              </div>
              <div style="float:left; text-align:center; margin-top: 20px; margin-right: 10px;">
                <p style="font-size:30px;float:left; margin-right: 7px;"> 단</p> <p style="font-size:30px;float:left;" class="text-info"><?php echo (int)($morning_pro);
                if ($morning_kcal==0){
                  echo 0;
                }?></p>

              </div>
              <div style="float:left;text-align:center; margin-top: 20px;">
                <p style="font-size:30px; float:left; margin-right: 7px;"> 지</p><p style="font-size:30px; float:left;" class="text-info"> <?php echo (int)($morning_fat);
                if ($morning_kcal==0){
                  echo 0;
                }?></p>
              </div>
            </div>
          </div>

        </div>
      </div>

<br>
<br>

      <div style="text-align:center; margin-top: 70px;">
      <p style="width: 100%; font-size: 30px; "><?php
      $result3=$conn->query($sql3);
      if($is_morning==1){
        while($row = $result3->fetch_assoc()){
          if($row['eaten_serving']==0){
            echo $row['food_name'] ."  ". (int)($row['food_calory'])*(0.5)."  Kcal "."(0.5 인분)";
            echo nl2br("\n\n");
          }
          else{
            echo $row['food_name'] ."  ". (int)($row['food_calory'])*$row['eaten_serving']."  Kcal "."(".$row['eaten_serving']." 인분)";
            echo nl2br("\n\n");
          }
        }
      }
?>

        </p>
      </div>
    </div>


    <div class="tab-pane fade" id="lunch" role="tabpanel" aria-labelledby="profile-tab">
      <div class="card-body" id="camera_2" style="text-align: center;">

        <a href="app://2"><button class="btn btn-secondary"id="btn1">음식입력</button></a>
        <!--  <button type="submit"> <i class="fas fa-utensils cardi" style="font-size:100px;"aria-hidden="true"></i></button>-->

      </div>


      <?php
      if($is_lunch==1){
        $lunch_kcal =0;
        $lunch_car =0;
        $lunch_pro =0;
        $lunch_fat =0;
        while($row = $result4->fetch_assoc()) {
          if ($row['eaten_serving']==0){
            $lunch_kcal = $lunch_kcal+$row['food_calory']*0.5;
            $lunch_car = $lunch_car+$row['food_car']*0.5;
            $lunch_pro = $lunch_pro+$row['food_pro']*0.5;
            $lunch_fat = $lunch_fat+$row['food_fat']*0.5;
          }
          else{
            $lunch_kcal = $lunch_kcal+$row['food_calory']*$row['eaten_serving'];
            $lunch_car =  $lunch_car+$row['food_car']*$row['eaten_serving'];
            $lunch_pro =  $lunch_pro+$row['food_pro']*$row['eaten_serving'];
            $lunch_fat =  $lunch_fat+$row['food_fat']*$row['eaten_serving'];

          }
        }
      }
      ?>



      <div style="margin:20px 30px;" class="smallbar">
        <div style="text-align : center;">
          <br>
          <div style=" text-align:center;" data-toggle="modal" data-target="#exampleModal" >
            <div style="float: left;padding-bottom:3px;">
              <p style="font-size:40px; " > 칼로리 <?php echo (int)($lunch_kcal);
              if ($lunch_kcal==0){
                echo 0;
              }?></p>
            </div>

            <div style="float: right;">
              <div style="float:left; text-align:center; margin:20px 10px 0 100px;">
                <p style="font-size:30px; float:left; margin-right: 7px;"> 탄 </p><p style="font-size:30px;float:left;" class="text-info"><?php echo (int)($lunch_car);
                if ($lunch_kcal==0){
                  echo 0;
                }?></p>

              </div>
              <div style="float:left; text-align:center; margin-top: 20px; margin-right: 10px;">
                <p style="font-size:30px; float:left; margin-right: 7px;"> 단</p><p style="font-size:30px;float:left;" class="text-info"> <?php echo (int)($lunch_pro);
                if ($lunch_kcal==0){
                  echo 0;
                }?></p>

              </div>
              <div style="float:left;text-align:center; margin-top: 20px;">
                <p style="font-size:30px; float:left; margin-right: 7px;"> 지 </p><p style="font-size:30px; float:left;" class="text-info"><?php echo (int)($lunch_fat);
                if ($lunch_kcal==0){
                  echo 0;
                }?></p>
              </div>
            </div>
          </div>

        </div>
      </div>

<br>
<br>

      <div style="text-align: center; margin-top: 70px;">
        <p style="width: 100%; font-size: 30px;"> <?php
      $result4=$conn->query($sql4);
      if($is_lunch==1){
        while($row = $result4->fetch_assoc()){
          if($row['eaten_serving']==0){
            echo $row['food_name'] ."  ". (int)($row['food_calory'])*(0.5)."  Kcal "."(0.5 인분)";
            echo nl2br("\n\n");
          }
          else{
            echo $row['food_name'] ."  ". (int)($row['food_calory'])*$row['eaten_serving']."  Kcal "."(".$row['eaten_serving']." 인분)";
            echo nl2br("\n\n");
          }
        }
      }
?> </p>

      </div>


    </div>



    <div class="tab-pane fade" id="dinner" role="tabpanel" aria-labelledby="contact-tab">


      <div class="card-body" id="camera_3" style="text-align: center;">

        <a href="app://3"><button class="btn btn-secondary"id="btn1">음식입력</button></a>
      </div>


      <?php
      if($is_dinner==1){
        $dinner_kcal =0;
        $dinner_car =0;
        $dinner_pro =0;
        $dinner_fat =0;
        while($row = $result5->fetch_assoc()) {
          if ($row['eaten_serving']==0){
            $dinner_kcal = $dinner_kcal+$row['food_calory']*0.5;
            $dinner_car = $dinner_car+$row['food_car']*0.5;
            $dinner_pro = $dinner_pro+$row['food_pro']*0.5;
            $dinner_fat = $dinner_fat+$row['food_fat']*0.5;
          }
          else{
            $dinner_kcal = $dinner_kcal+$row['food_calory']*$row['eaten_serving'];
            $dinner_car =  $dinner_car+$row['food_car']*$row['eaten_serving'];
            $dinner_pro =  $dinner_pro+$row['food_pro']*$row['eaten_serving'];
            $dinner_fat =  $dinner_fat+$row['food_fat']*$row['eaten_serving'];

          }
        }
      }
      ?>



      <div style="margin:20px 30px;" class="smallbar">
        <div style="text-align : center;">
          <br>
          <div style=" text-align:center;" data-toggle="modal" data-target="#exampleModal" >
            <div style="float: left;padding-bottom:3px;">
              <p style="font-size:40px; " > 칼로리 <?php echo (int)($dinner_kcal);
              if ($dinner_kcal==0){
                echo 0;
              }?></p>
            </div>

            <div style="float: right;">
              <div style="float:left; text-align:center; margin:20px 10px 0 100px;">
                <p style="font-size:30px; float:left; margin-right: 7px;"> 탄 </p><p style="font-size:30px;float:left;" class="text-info"><?php echo (int)($dinner_car);
                if ($dinner_kcal==0){
                  echo 0;
                }?></p>

              </div>
              <div style="float:left; text-align:center; margin-top: 20px; margin-right: 10px;">
                <p style="font-size:30px; float:left; margin-right: 7px;"> 단</p><p style="font-size:30px;float:left;" class="text-info"> <?php echo (int)($dinner_pro);
                if ($dinner_kcal==0){
                  echo 0;
                }?></p>

              </div>
              <div style="float:left;text-align:center; margin-top: 20px;">
                <p style="font-size:30px; float:left; margin-right: 7px;"> 지</p><p style="font-size:30px; float:left;" class="text-info"> <?php echo (int)($dinner_fat);
                if ($dinner_kcal==0){
                  echo 0;
                }?></p>
              </div>
            </div>
          </div>

        </div>
      </div>
<br>
<br>


      <div style="text-align: center; margin-top: 70px;">
        <p style="width: 100%; font-size: 30px;"> <?php
      $result5=$conn->query($sql5);
      if($is_dinner==1){
        while($row = $result5->fetch_assoc()){
          if($row['eaten_serving']==0){
            echo $row['food_name'] ."  ". (int)($row['food_calory'])*(0.5)."  Kcal "."(0.5 인분)";
            echo nl2br("\n\n");
          }
          else{
            echo $row['food_name'] ."  ". (int)($row['food_calory'])*$row['eaten_serving']."  Kcal "."(".$row['eaten_serving']." 인분)";
            echo nl2br("\n\n");
          }
        }
      }
?> </p>

      </div>







    </div>
  </div>
</div>

<br>
<br>
<br>

<div class="card">
  <div class="card-header rea" style="text-align:center;">
   식단추천
 </div>
 <div class="card-body">
  <div id="container">
    <div class="box">
      <table class="table table-hover" id="user-table">
    <!--
    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <span class="input-group-text" id="basic-addon1">칼로리</span>
      </div>
      <input type="number" class="form-control" id="keyword" placeholder="Food Name" aria-label="Username" aria-describedby="basic-addon1">
    </div>-->
    <div style="text-align:center;">
      <input style="font-size : 50px; width:50%; height:100px; text-align:center;"type="text" id="myInput" onkeyup="myFunction()" placeholder="칼로리입력">
    </div> <thead>
      <br><br><br>
      <tr class="tile">
        <th>식단번호</th>
        <th>곡류</th>
        <th>고기류</th>
        <th>채소류</th>
        <th>기타</th>
        <th>칼로리</th>
      </tr>
    </thead>

    <tbody>
      <?php
#   header('Content-Type:text/html; charset=UTF-8');


      $today=date("Y-m-d");
      $jb_conn = mysqli_connect( 'localhost', 'root', 'toor', 'smartpt' );


      mysqli_query($jb_conn, "set session character_set_connection=utf8;");

      mysqli_query($jb_conn, "set session character_set_results=utf8;");

      mysqli_query($jb_conn, "set session character_set_client=utf8;");


      require_once("./dbconn.php");
      $user_id = 1; # 1번 가져왔다고 가정
      $sql = "SELECT * FROM user WHERE user_id='$user_id'";
      $result = $conn->query($sql);
      if ($result->num_rows > 0) { // 여러줄 가져오는 경우
        while($row = $result->fetch_assoc()) {
          $user = $row;
        }
      } else {
        echo "유저 접속 오류";
      }



      $maxcar = $user['user_goal'] * 0.65;
      $maxfat =$user['user_goal'] * 0.2;
      $maxpro =$user['user_goal'] * 0.15;


      $sql1 = "SELECT * FROM diet";
      $result1 = $conn->query($sql1);
      while($row=mysqli_fetch_array( $result1 ) ) {
        echo '<tr style="display:none"><td>' . $row['diet_id']. '</td><td>'. $row['diet_grains'] . '</td><td>' .$row['diet_meat']. '</td><td>'. $row['diet_vet'] .'</td><td>'. $row['diet_else'] .'</td><td>'. $row['diet_calory'] .'</td></tr>';

      }

/*
$arr= array();
$arr_diet=array();
if ($result1->num_rows > 0) { // 여러줄 가져오는 경우
while($row = $result1->fetch_assoc()) {
array_push($arr,array($row['diet_id'],$row['diet_grains'],$row['diet_meat'],$row['diet_vet'],$row['diet_else'],$row['diet_calory']));
#  echo $row['diet_id'] ." 번식단 밥종류:" .$row['diet_grains'] ." 고기종류: ".$row['diet_meat']."채소 종류 ".$row['diet_vet']."기타: ".$row['diet_else']."칼로리 :".$row['diet_calory'];
#   echo nl2br("\n");
}
} else {
echo "0 results";
}
if($inputKcal> $arr[$i][5]){
#         echo $arr[$i][5];
array_push($arr_diet,array($arr[$i][0],$arr[$i][1],$arr[$i][2],$arr[$i][3],$arr[$i][4],$arr[$i][5]));
}
#   echo nl2br("\n");
}
for($i=0;$i<count($arr_diet)-1;$i++){
for($j=0;$j<count($arr_diet)-1;$j++){
if($arr_diet[$j][5]>$arr_diet[$j+1][5]){
    $temp=$arr_diet[$j];
    $arr_diet[$j]=$arr_diet[$j+1];
    $arr_diet[$j+1]=$temp;
}
}
}
#for($i=0;$i<count($arr_diet);$i++){
#echo '<tr><td>' . $arr_diet[$i][0]. '</td><td>'. $arr_diet[$i][1] . '</td><td>' .$arr_diet[$i][2]. '</td><td>'. $arr_diet[$i][3] .'</td><td>'. $arr_diet[$i][4] .'</td><td>'. $arr_diet[$i][5] .'</td></tr>';
#}
*/
?>
</tbody>
</table>
</div>
</div>

</div>
</div>

<br /><br /><br />





<nav class="navbar fixed-bottom navd">
  <a href="index.php">
   <div class="navIcons" style="text-align:center;">
    <br/>
    <i class="navIcon fas fa-home navdi" id="navHome" aria-hidden="true"></i>
    <p class="navName navdp">
     Home
   </p>
 </div>
</a>
<a href="recommend.php">
  <div class="navIcons" style="text-align:center;">
    <br/>
    <i class="navIcon fas fa-utensils navdi" id="navDiet" aria-hidden="true"></i>
    <p class="navName navdp">
      Diet
    </p>
  </div>
</a>
<a href="static.php">
  <div class="navIcons" style="text-align:center;">
    <br/>
    <i class="navIcon far fa-chart-bar navdi" id="navChart" aria-hidden="true"></i>
    <p class="navName navdp">
      Chart
    </p>
  </div>
</a>
</nav>
</body>
</html>
