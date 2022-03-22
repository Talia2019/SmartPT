
  
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <link
            href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
            rel="stylesheet"
            integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN"
            crossorigin="anonymous">

        <link rel="stylesheet" href="/resource/css/bootstrap.css">
        <link
            rel="stylesheet"
            href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
            integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
            crossorigin="anonymous">
        <!-- 폰트 -->
        <link
            href="https://fonts.googleapis.com/css2?family=Black+Han+Sans&family=Do+Hyeon&display=swap"
            rel="stylesheet">
        <script src="https://use.fontawesome.com/releases/v5.2.0/js/all.js"></script>
        <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
        <script
            src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
            integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
            crossorigin="anonymous"></script>
        <script
            src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
            integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
            crossorigin="anonymous"></script>
        <meta charset="utf-8">

        <style type="text/css">

            #container {
                width: 960px;
                margin: 0 auto;
            }
            #container #input-form {
                text-align: center;
            }
            #user-table {
                margin: 0 auto;
                text-align: center;
            }
            #input-form {
                margin-top: 10px;
                margin-bottom: 10px;
            }
        </style>

        <script type="text/javascript">
            $(document).ready(function () {
            })

            function clickTd() {
                $("#user-table tr").click(function () {
                    var text = $(this).text();
                    alert('선택한 식단번호 : ' + text[0]);
                    document.getElementById("dietnum").value = text[0];
                });

            }
        </script>

    </head>

    <body>

        <div id="container">


                <table class="table table-hover" id="user-table">
                    <thead>
                        <tr>
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


        $inputKcal=$_POST["inputKcal"];
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

	// 오늘 먹은 음식 조회
	$sql2 = "SELECT * FROM eatenfood INNER JOIN foodinfo on eatenfood.food_id = foodinfo.food_id WHERE user_id='$user_id' and eaten_day='$today'";
	$result1 = $conn->query($sql2);

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

    echo $kcal;
    
        $sql1 = "SELECT * FROM diet";
        $result1 = $conn->query($sql1);
        $arr= array();
        $arr_diet=array();
        if ($result1->num_rows > 0) { // 여러줄 가져오는 경우
          while($row = $result1->fetch_assoc()) {
              array_push($arr,array($row['diet_id'],$row['diet_grains'],$row['diet_meat'],$row['diet_vet'],$row['diet_else'],$row['diet_calory']));
            #  echo $row['diet_id'] ." 번식단 밥종류:" .$row['diet_grains'] ." 고기종류: ".$row['diet_meat']."채소 종류 ".$row['diet_vet']."기타: ".$row['diet_else']."칼로리 :".$row['diet_calory'];
          #		echo nl2br("\n");
              }
        } else {
          echo "0 results";
        }


        for($i=0;$i<count($arr);$i++){
            #for($j=0;$j<6;$j++){	
            #	echo $arr[$i][$j];
            #}
            if($inputKcal> $arr[$i][5]){
#					echo $arr[$i][5];

                array_push($arr_diet,array($arr[$i][0],$arr[$i][1],$arr[$i][2],$arr[$i][3],$arr[$i][4],$arr[$i][5]));

            }
    #		echo nl2br("\n");
            
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
        for($i=0;$i<count($arr_diet);$i++){
			echo '<tr><td>' . $arr_diet[$i][0]. '</td><td>'. $arr_diet[$i][1] . '</td><td>' .$arr_diet[$i][2]. '</td><td>'. $arr_diet[$i][3] .'</td><td>'. $arr_diet[$i][4] .'</td><td>'. $arr_diet[$i][5] .'</td></tr>';
   
        }
        
      ?>
                    </tbody>
                </table>
            </div>

            <form></form>

        </body>
    </html>
