<?php
session_start();

require_once("./dbconn.php");
  if(!isset($_SESSION['code'])){
    $_SESSION['code']=$_GET['code'];
  }
  $user_code = $_SESSION['code'];

  $today = date("Y-m-d");
  $walking;
  $running;
  $kcal = 0; //칼로리
  $car =0; // 탄수화물
  $fat =0; // 지방
  $pro =0; // 단백질
  $walking_calory = 3;
  $running_calory = 6 ;
  $recommend_walking;
  $recommend_running;
  $user;
  $sql = "SELECT * FROM user WHERE user_code='$user_code'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) { // 여러줄 가져오는 경우

    while($row = $result->fetch_assoc()) {
      $user_id = $row['user_id'];
      $_SESSION['id'] = $user_id;
      $user = $row;
    }
  } else {
    Header("Location:./userinsert.php",$_SESSION['code']);
  }

  $maxcar =$user['user_goal'] * 0.65 * 0.25;
  $maxfat =$user['user_goal'] * 0.2 * 0.1;
  $maxpro =$user['user_goal'] * 0.15 * 0.25;

  $maxcar = (int)($maxcar);
  $maxfat = (int)($maxfat);
  $maxpro = (int)($maxpro);

  $sql1 = "SELECT * FROM doexercise INNER JOIN exerciseinfo on doexercise.exercise_id = exerciseinfo.exercise_id WHERE user_id='$user_id' and doexercise_day='$today'";
  $result1 = $conn->query($sql1);

  if ($result1->num_rows > 0) { // 여러줄 가져오는 경우

    while($row = $result1->fetch_assoc()) {
      if($row['exercise_id']==1)
      {
        $walking = $row;
      }
      else if($row['exercise_id']==2)
      {
        $running = $row;
      }
    // echo $row['exercise_name'] ." / " .$row['doexercise_minute'] ."분 / ".$row['doexercise_calory']."Kcal";
    }
  }
  if($walking==null){
    $walking['doexercise_calory']=0;
    $walking['doexercise_minute']=0;
    $walking['doexercise_distance']=0;
  }
  if($running==null){
    $running['doexercise_calory']=0;
    $running['doexercise_minute']=0;
    $running['doexercise_distance']=0;
  }

  $sql2 = "SELECT * FROM eatenfood INNER JOIN foodinfo on eatenfood.food_id = foodinfo.food_id WHERE user_id='$user_id' and eaten_day='$today'";
  $result2 = $conn->query($sql2);

  if ($result2 -> num_rows>0) { // 여러줄 가져오는 경우

  while($row = $result2->fetch_assoc()) {
    if($row['eaten_serving']==0){ # 1/2인분인경우
      $kcal = $kcal + $row['eaten_calory'];
      $car = $car + $row['food_car']*0.5;
      $fat = $fat + $row['food_fat']*0.5;
      $pro = $pro + $row['food_pro']*0.5;
   }
   else{
    $kcal = $kcal + $row['eaten_calory'];
    $car = $car + $row['food_car']*$row['eaten_serving'];
    $fat = $fat + $row['food_fat']*$row['eaten_serving'];
    $pro = $pro + $row['food_pro']*$row['eaten_serving'];
   }
  #echo $row['exercise_name'] ." / " .$row['exercise_minute'] ."분 / ".$row['exhausted_calory']."Kcal";
  #echo nl2br("\n");
  }

  }

  $recommend_walking = ($kcal-$user['user_goal'])/$walking_calory;
  $recommend_running = ($kcal-$user['user_goal'])/$running_calory;

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
  <link rel="stylesheet" href="./css/jaehyun.css?ver=5">
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
  <script src="./js/nav.js?ver=1"></script>
</head>

<body>
  <nav class="navbar fixed-top">
    <p class="navp">SmartPT</p>
    <a href="usermodify.php">
      <i class="fa fa-user-circle navi"></i>
    </a>
  </nav>
  <br><br><br>
  <div style="margin-left:50px;">
    <div style="margin-bottom:50px;">
      <p style="font-size:80px; float:left; margin-top:10px;">오늘의 운동</p>
      <div>
        <a href="band://">
          <i class="fa fa-refresh fa-fw" style="font-size:60px; color:#BDBDBD; margin-top:20px; margin-left:50px;"></i>
          <p style="margin-left:380px; font-size:40px; color:#BDBDBD;">미밴드</p>
        </a>
      </div>
    </div>
    <p class="text-muted" style="font-size:70px;">Today's exercise</p>
  </div>
  <br>
  <div class="card exercise-card" style="float:left; margin:0 100px;">
    <div class="card-body">
        <img src="./walking.png"  width="100" height="100"/>
        <p style="font-size:60px; margin-bottom:0; color:#f38181;"><?php echo (int)($walking['doexercise_calory']);?></p>
        <p style="font-size:40px; color:gray;">Kcal</p>
        <div>
          <p style="font-size:30px; float:left; margin-left:40px;"><?php echo (int)($walking['doexercise_minute']);?> min</p>
          <p style="font-size:30px; "><?php echo (int)($walking['doexercise_distance']);?> m</p>
       </div>
    </div>
  </div>

  <div class="card exercise-card" style="margin-right:100px;">
    <div class="card-body">
        <img src="./running.png"  width="100" height="100"/>
        <p style="font-size:60px; margin-bottom:0; color:#f38181;"><?php echo (int)($running['doexercise_calory']);?></p>
        <p style="font-size:40px; color:gray;">Kcal</p>
        <div>
          <p style="font-size:30px; float:left; margin-left:40px;"><?php echo (int)($running['doexercise_minute']);?> min</p>
          <p style="font-size:30px; "><?php echo (int)($running['doexercise_distance']);?> m</p>
        </div>
    </div>
  </div>

  <br><br><br><br>

  <div style="margin-left:50px;">
    <p style="font-size:80px;">오늘의 영양소</p>
    <p class="mb-2 text-muted" style="font-size:70px;">Today's nutrients</p>
  </div>
  <br>
  <div class="card nutrients-card" style="margin:0 100px; height:500px;">
    <div class="card-body">
      <br>
      <div id="calorydiv"style="text-align:center">
        <p style="margin-right:20px; font-size:40px;" > 칼로리 </p>
        <div class="progress" style="height:30px;" >
          <div class="progress-bar bg-success" id="progress_kcal" role="progressbar" style="width:  <?php echo (int)($kcal)/$user['user_goal']*100;?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <p style="font-size:30px;"><?php echo (int)($kcal)."/".$user['user_goal'];?></p>
        <div id="notification_div" style="position:absolute; right:10%; width:200px; ">
          <i class="fas fa-exclamation-circle misi" style="font-size:30px;"></i>
          <p style="font-size:20px; ">총 <?php echo ((int)($kcal)-$user['user_goal']);?> Kcal 초과</p>
        </div>
      </div>

      <!-- Modal -->
      <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog" >
          <div class="modal-content" style="width:600px; height:600px; display:table;" >
            <div style="text-align:center;">
              <br>
              <h1 class="modal-title" id="exampleModalLabel">추천운동</h1>
              <p style="font-size:50px;"><?php echo ((int)($kcal)-$user['user_goal']);?> kcal</p>
            </div>
            <div class="modal-body">
              <div class="card exercise-card" style="float:left; width:200px; height:300px; margin-left:50px;">
                <div class="card-body">
                    <img src="./walking.png"  width="70" height="70"/>
                    <p style="font-size:60px; margin-bottom:0; color:#f38181;"><?php echo  (int)($recommend_walking);?></p>
                    <p style="font-size:35px; color:gray;">Min</p>
                </div>
              </div>
              <div class="card exercise-card" style=" width:200px; height:300px;">
                <div class="card-body">
                    <img src="./running.png"  width="70" height="70"/>
                    <p style="font-size:60px; margin-bottom:0; color:#f38181;"><?php echo (int)($recommend_running);?></p>
                    <p style="font-size:35px; color:gray;">Min</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>



      <br><br><br>
      <div class="carprofat">
        <div style="float:left; width:150px; text-align:center; margin:0 70px;">
          <p style="font-size:40px;"> 탄수화물 </p>
          <div class="progress" >
            <div class="progress-bar bg-info" role="progressbar" style="width: <?php echo $car/$maxcar*100;?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"> </div>
          </div>
          <p style="font-size:25px;"><?php echo (int)($car)."/".$maxcar;?></p>
        </div>
        <div style="float:left; width:150px; text-align:center; margin-right: 70px;">
          <p style="font-size:40px;"> 단백질 </p>
          <div class="progress" >
            <div class="progress-bar bg-info" role="progressbar" style="width: <?php echo $pro/$maxpro*100;?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"> </div>
          </div>
          <p style="font-size:25px;"><?php echo (int)($pro)."/".$maxpro;?></p>
        </div>
        <div style="float:left; width:150px; text-align:center;">
          <p style="font-size:40px;"> 지방 </p>
          <div class="progress" >
            <div class="progress-bar bg-info" role="progressbar" style="width: <?php echo $fat/$maxfat*100;?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
          <p style="font-size:25px;"><?php echo (int)($fat)."/".$maxfat;?></p>
        </div>
      </div>
    </div>
  </div>


  <br><br><br><br>
  <nav class="navbar fixed-bottom navd">
    <a href="index.php">
      <div class="navIcons" style="text-align:center;">
        <br />
        <i class="navIcon fas fa-home navdi" id="navHome" aria-hidden="true" style="color:#8DA5BD;"></i>
        <p class="navName navdp" style="color:#8DA5BD;"> Home </p>
      </div>
    </a>
    <a href="recommend.php">
      <div class="navIcons" style="text-align:center;">
        <br />
        <i class="navIcon fas fa-utensils navdi" id="navDiet" aria-hidden="true"></i>
        <p class="navName navdp"> Diet </p>
      </div>
    </a>
    <a href="static.php">
      <div class="navIcons" style="text-align:center;">
        <br />
        <i class="navIcon far fa-chart-bar navdi" id="navChart" aria-hidden="true"></i>
        <p class="navName navdp"> Chart </p>
      </div>
    </a>
  </nav>

</body>
<script>

  var kcal = Number('<?php echo $kcal?>');
  var goal = Number('<?php echo $user['user_goal']?>');


  if(kcal>goal){
    document.getElementById('progress_kcal').className="bg-danger";
    document.getElementById('notification_div').style.display='block';
  }
  else{
    document.getElementById('progress_kcal').className="bg-success";
    document.getElementById('notification_div').style.display='none';
  }
  $(function(){
    $("#calorydiv").click(function(){
      if(kcal>goal) $('div.modal').modal();
    })
})

</script>
</html>
