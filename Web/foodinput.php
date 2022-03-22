<?php 
session_start();

$_SESSION['code']=$_GET['code'];
?>
<!DOCTYPE html>
<html>
<head>
  <title></title>
  <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

  <link rel="stylesheet" href="/resource/css/bootstrap.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <!-- 폰트 -->
  <link href="https://fonts.googleapis.com/css2?family=Black+Han+Sans&family=Do+Hyeon&display=swap" rel="stylesheet">
  <script src="https://use.fontawesome.com/releases/v5.2.0/js/all.js"></script>
  <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <meta charset="utf-8">



  <style type="text/css">
    .box {
      height:1000px;
      overflow-y:scroll;
    }


    #container {width: 960px; margin: 0 auto;}
    #container #input-form {text-align: center;}
    #user-table {margin: 0 auto; text-align: center;}
    #input-form {margin-top: 10px; margin-bottom: 10px;}
    span{
      font-size:30px;
    }


    .hi{
      font-size:30px;
    }
  </style>

  <script type="text/javascript">

    $(document).ready(function() {
      clickTd();      
      $("#keyword").keyup(function() {
        var k = $(this).val();
        $("#user-table > tbody > tr").hide();
        var temp = $("#user-table > tbody > tr > td:nth-child(5n+2):contains('" + k + "')");

        $(temp).parent().show();
      })
    })
    
    

    
    function clickTd(){
      $("#user-table tr").click(function(){

        var tdArr = new Array();
        var tr = $(this);
        var td = tr.children();
        var val=$(td[0]).html();
       // alert(val);
       alert('선택한 음식번호 : '+val);
       document.getElementById("foodnum").value=val;
     });
    }


  </script>
  <link rel="stylesheet" href="./css/jaehyun.css?ver=2">

</head>



<body>

  <div clas="container" style="text-align: center;">
    <p class="title">Food Input</p>
  </div>
  
  <div id="container">
    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <span class="input-group-text hi" id="basic-addon1">음식이름</span>
      </div>
      <input type="text" class="form-control hi" id="keyword" placeholder="Food Name" aria-label="Username" aria-describedby="basic-addon1">
    </div>
  </div>
  <br>
  
  <form method="post" action="updatefoodinput2.php">
    <div class="row">

      <div class="input-group mb-3 col">
        <div class="input-group-prepend">
          <span class="input-group-text hi" id="basic-addon1">번호</span>
        </div>
        <input type="text" class="form-control hi" name="foodnum" id="foodnum" placeholder="Food Number" aria-label="Username" aria-describedby="basic-addon1">
      </div>
      <div class="input-group mb-3 col">
        <div class="input-group-prepend">
          <span class="input-group-text hi" id="basic-addon1">음식인분</span>
        </div>
        <input type="number" class="form-control hi" name="serving" placeholder="Serving" aria-label="Username" aria-describedby="basic-addon1">
      </div>
      <input type="hidden" name="eaten_time" value="<?php echo $_GET["eaten_time"];?>">
      <div style="width: 100%; text-align: center;">
        <button class="btn btn-secondary" style="font-size:50px;width:80%; height: 80px;" type="submit"> 제출 </button>
      </div>
    </div>
  </form>


  <div class="box">
    <table class="table table-hover " id="user-table" style=" width:100%;margin-top: 30px;">
      <thead >
        <tr>
          <th>번호</th>
          <th>음식이름</th>
          <th>탄</th>
          <th>단</th>
          <th>지</th>
          <th>칼로리</th>
        </tr>
      </thead>

      <tbody>
        <?php
        #   header('Content-Type:text/html; charset=UTF-8');


        $jb_conn = mysqli_connect( 'localhost', 'root', 'toor', 'smartpt' );


        mysqli_query($jb_conn, "set session character_set_connection=utf8;");

        mysqli_query($jb_conn, "set session character_set_results=utf8;");

        mysqli_query($jb_conn, "set session character_set_client=utf8;");


        $jb_sql = "SELECT * FROM foodinfo;";
        $jb_result = mysqli_query( $jb_conn, $jb_sql );




        while( $jb_row = mysqli_fetch_array( $jb_result ) ) {
          echo '<tr><td>' . $jb_row[ 'food_id' ] . '</td><td>'. $jb_row[ 'food_name' ] . '</td><td>' . $jb_row[ 'food_car' ] . '</td><td>'. $jb_row[ 'food_pro' ] .'</td><td>'. $jb_row[ 'food_fat' ] .'</td><td>'. $jb_row[ 'food_calory' ] .'</td></tr>';
        }
        ?>
      </tbody>
    </table>
  </div>
</body>
</html>
